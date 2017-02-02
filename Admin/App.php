<?php
namespace Admin;

use Actions\Member;
use Library\Presentation;

class App extends Presentation {

    /**
     * Reset password for user
     */
    public function resetPassword()
    {
        if ($this->isPost() && isset($this->data['password']) && isset($this->data['id']) && !empty($this->data['id'])) {
            // Check user exist
            $user = Member::getById($this->data['id']);

            if ($user) {
                // Update password for user
                if (Member::updatePassword($this->data['id'], $this->data['password'])) {
                    return json_encode(['success' => true]);
                }
            }
        }

        return json_encode(['success' => false]);
    }

    /**
     * Remove user
     */
    public function removeUser()
    {
        if ($this->isPost() && isset($this->data['id']) && !empty($this->data['id'])) {
            // Update status: -1
            if (Member::updateInfo($this->data['id'], ['status' => '-1'])) {
                return json_encode(['success' => true]);
            }
        }

        return json_encode(['success' => false]);
    }
}