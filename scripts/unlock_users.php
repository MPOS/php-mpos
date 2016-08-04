#!/usr/bin/php
<?php
/*

Copyright:: 2014, Mining Portal Open Source
https://github.com/orgs/MPOS/members

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

 */
        /* script to unlock and email users */

        // Change to working directory
        chdir(dirname(__FILE__));

        // Include all settings and classes
        require_once('shared.inc.php');

        // Fetch all users
        $users = $user->getAllAssoc();

        $currentTime = time();

        foreach ($users as $usr)
        {
                $id = $usr['id'];
                $isAdmin = $usr['is_admin'];
                $username = $usr['username'];
                $loggedIp = $usr['loggedIp'];
                $lastLogin  = $usr['last_login'];
                $coinAddress = $usr['coin_address'];
                $isLocked = $usr['is_locked'];
                $email = $usr['email'];
                $everLoggedIn = !empty($lastLogin);
                $timeDelta = $currentTime - $lastLogin;
                $lastLoginInDays = round(abs($timeDelta)/60/60/24, 0);

                if ($isLocked == 1) {
                    printf("%s is locked, unlocking...\n", $username);
                    $user->setLocked($id,0);
                    $aData['username'] = $username;
                    $aData['email'] = $email;
                    $aData['subject'] = 'Weekly Auto Unlock';
                    $mail->sendMail('notifications/unlocked', $aData);
                }
        }

?>


