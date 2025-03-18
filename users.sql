-- Update existing users to have 30 sessions (except admin)
UPDATE `user` 
SET `remaining_sesssion` = 30 
WHERE `role` = 'user' AND `remaining_sesssion` = 0;

-- Ensure new users get 30 sessions by default (already in your schema)
ALTER TABLE `user` 
MODIFY `remaining_sesssion` int(11) NOT NULL DEFAULT 30;
