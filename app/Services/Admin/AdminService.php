<?php

namespace App\Services\Admin;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Admin;
use App\Models\AdminsRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminService {
	
    public function login($data) {
        $admin = Admin::where('email', $data['email'])->first();

        if ($admin) {
            if ($admin->status == 0) {
                return "Inactive"; // Return status if the account is inactive
            }

            if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 1])) {
                // Remember Admin Email and Password
                if (!empty($data["remember"])) {
                    setcookie("email", $data["email"], time() + 3600);
                    setcookie("password", $data["password"], time() + 3600);
                } else {
                    setcookie("email", "");
                    setcookie("password", "");
                }
                return "success"; // Return success if login is successful
            } else {
                return "Invalid"; // Return invalid if credentials are incorrect
            }
        } else {
            return "Invalid"; // Return invalid if email is not found
        }
    }


/*     public function verifyPassword($data){
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            return "true";
        } else {
            return "false";
        }
    } */
   public function verifyPassword($data)
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        if ($admin && Hash::check($data['current_pwd'], $admin->password)) {
            return "true"; // Password is correct
        }

        return "false"; // User not logged in or password incorrect
    }

    public function updatePassword($data){
        // Check if the Current Password is correct
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            // Check if new password and confirm password match
            if ($data['new_pwd'] == $data['confirm_pwd']) {
                Admin::where('email', Auth::guard('admin')->user()->email)
                    ->update(['password' => bcrypt($data['new_pwd'])]);
                $status = "success";
                $message = "Password has been updated successfully!";
            } else {
                $status = "error";
                $message = "New Password and Confirm Password must match!";
            }
        } else {
            $status = "error";
            $message = "Your current password is incorrect!";
        }
        return ["status" => $status, "message" => $message];
    }


    
    public function updateDetails($request){
        $data = $request->all();

        // Upload Admin Image  
        if ($request->hasFile('image')) {  
            $image_tmp = $request->file('image');  
            if ($image_tmp->isValid()) {  
                $manager = new ImageManager(new Driver());  // ✅ Use ImageManager
                $image = $manager->read($image_tmp);  
                $extension = $image_tmp->getClientOriginalExtension();  
                $imageName = rand(111,99999).'.'.$extension;  
                $image_path = public_path('admin/images/photos/'.$imageName);  // ✅ Use `public_path()` to save images  
                $image->save($image_path);  
            }  
        } else if (!empty($data['current_image'])) {  
            $imageName = $data['current_image'];  
        } else {  
            $imageName = "";  
        }  

        // Update Admin Details
        Admin::where('email', Auth::guard('admin')->user()->email)->update([
            'name' => $data['name'],
            'mobile' => $data['mobile'],  
            'image' => $imageName  
        ]);
    }

    public function deleteProfileImage($adminId)
    {
        $profileImage = Admin::where('id', $adminId)->value('image');
        if ($profileImage) {
            $profile_image_path = 'admin/images/photos/' . $profileImage;
        if (file_exists(public_path($profile_image_path))) {
                unlink(public_path($profile_image_path));
            }
        Admin::where('id', $adminId)->update(['image' => null]);
        return ['status' => true, 'message' => 'Profile image deleted successfully!'];
        }
        return ['status' => false, 'message' => 'Profile image not found!'];
    }

    public function subadmins() {
        $subadmins = Admin::where('role', 'subadmin')->get();
        return $subadmins;
    }

    public function updateSubadminStatus($data) {
        $status = ($data['status'] == "Active") ? 0 : 1;
        Admin::where('id', $data['subadmin_id'])->update(['status' => $status]);
        return $status;
    }

    public function deleteSubadmin($id){
        // Delete Sub Admin
        Admin::where('id', $id)->delete();
        $message = 'Subadmin deleted successfully!';
        return array("message" => $message);
    }

    public function addEditSubadmin($request){
        $data = $request->all();
    
        if(isset($data['id']) && $data['id']!=""){
            $subadmindata = Admin::find($data['id']);
            $message = "Subadmin updated successfully!";
        } else {
            $subadmindata = new Admin;
            $message = "Subadmin added successfully!";    
        }
    
        // Upload Admin Image
        if($request->hasFile('image')){
            $image_tmp = $request->file('image');
            if($image_tmp->isValid()){
                // create image manager with desired driver
                $manager = new ImageManager(new Driver());
    
                // read image from file system
                $image = $manager->read($image_tmp);
    
                // Get Image Extension
                $extension = $image_tmp->getClientOriginalExtension();
    
                // Generate New Image Name
                $imageName = rand(111,99999).'.'.$extension;
                $image_path = 'admin/images/photos/'.$imageName;
    
                // save image in specified path
                $image->save($image_path);
            }
        } else if(!empty($data['current_image'])){
            $imageName = $data['current_image'];
        } else {
            $imageName = "";
        }
    
        $subadmindata->image = $imageName;
        $subadmindata->name = $data['name'];
        $subadmindata->mobile = $data['mobile'];
        
        if(!isset($data['id'])){
            $subadmindata->email = $data['email'];
            $subadmindata->role = 'subadmin';
            $subadmindata->status = 1;
        }
        
        if($data['password']!=""){
            $subadmindata->password = bcrypt($data['password']);
        }
    
        $subadmindata->save();
        return array("message"=>$message);
    }

        public function updateRole($request) {
            $data = $request->all();
            
            // Remove existing roles before updating
            AdminsRole::where('subadmin_id', $data['subadmin_id'])->delete();
            
            // Assign new roles dynamically
            foreach ($data as $key => $value) {
                if (!is_array($value)) continue; // Skip non-module fields
                
                $view = isset($value['view']) ? $value['view'] : 0;
                $edit = isset($value['edit']) ? $value['edit'] : 0;
                $full = isset($value['full']) ? $value['full'] : 0;
            AdminsRole::insert([
                    'subadmin_id' => $data['subadmin_id'],
                    'module' => $key,
                    'view_access' => $view,
                    'edit_access' => $edit,
                    'full_access' => $full
                ]);
            }
            return ['message' => 'Subadmin Roles updated successfully!'];
        }



}

