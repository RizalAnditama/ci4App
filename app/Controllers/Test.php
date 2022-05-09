<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Test extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('pages/my-form', ['title' => 'Test']);


        if ($this->request->getMethod() == "post") {

            $rules = [
                "name" => "required|min_length[3]|max_length[40]",
                "email" => "required|valid_email",
                "phone_no" => "required|min_length[9]|max_length[15]",
                "profile_image" => [
                    "rules" => "uploaded[profile_image]|max_size[profile_image,1024]|is_image[profile_image]|mime_in[profile_image,image/jpg,image/jpeg,image/gif,image/png]",
                    "label" => "Profile Image",
                ],
            ];

            if (!$this->validate($rules)) {

                return view("my-form", [
                    "validation" => $this->validator,
                ]);
            } else {

                $file = $this->request->getFile("profile_image");

                $session = session();
                $profile_image = $file->getName();

                if ($file->move("images", $profile_image)) {

                    $userModel = new UserModel();

                    $data = [
                        "name" => $this->request->getVar("name"),
                        "email" => $this->request->getVar("email"),
                        "phone_no" => $this->request->getVar("phone_no"),
                        "profile_pic" => "/images/" . $profile_image,
                    ];

                    if ($userModel->insert($data)) {

                        $session->setFlashdata("success", "Data saved successfully");
                    } else {

                        $session->setFlashdata("error", "Failed to save data");
                    }
                }
            }
            return redirect()->to(base_url());
        }
        return view("my-form");
    
    }
}
