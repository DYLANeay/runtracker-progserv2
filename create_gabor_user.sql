-- Script to create user 'gabor' with sample runs
-- Username: gabor
-- Password: gabor123
-- This script can be executed directly in phpMyAdmin

USE runtracker;

-- Insert user 'gabor'
INSERT INTO users (username, email, password, created_at)
VALUES (
    'gabor',
    'gabor@example.com',
    '$2y$12$bK6EGTHu.PTbNZbeLV0VKeTr60jok1APLvSUlqn14ieIDRtOlGQbm',
    NOW()
);

-- Get the user_id for the runs (will be the last inserted ID)
SET @gabor_user_id = LAST_INSERT_ID();

-- Insert sample runs for gabor
INSERT INTO runs (user_id, date, distance, duration, pace, notes, created_at) VALUES
(@gabor_user_id, '2025-11-01', 5.00, '00:30:00', '00:06:00', 'Morning run, felt energized!', NOW()),
(@gabor_user_id, '2025-11-03', 7.50, '00:48:00', '00:06:24', 'Easy pace run', NOW()),
(@gabor_user_id, '2025-11-05', 10.00, '01:05:00', '00:06:30', 'Long weekend run', NOW()),
(@gabor_user_id, '2025-11-08', 5.00, '00:28:30', '00:05:42', 'Speed workout, pushed hard!', NOW()),
(@gabor_user_id, '2025-11-10', 8.00, '00:52:00', '00:06:30', 'Recovery run', NOW()),
(@gabor_user_id, '2025-11-12', 12.00, '01:20:00', '00:06:40', 'Half marathon training run', NOW()),
(@gabor_user_id, '2025-11-15', 6.00, '00:36:00', '00:06:00', 'Midweek steady run', NOW()),
(@gabor_user_id, '2025-11-17', 10.50, '01:08:15', '00:06:30', 'Beautiful weather for running!', NOW()),
(@gabor_user_id, '2025-11-20', 5.00, '00:29:00', '00:05:48', 'Interval training session', NOW()),
(@gabor_user_id, '2025-11-22', 15.00, '01:45:00', '00:07:00', 'Long slow distance run', NOW());

-- Verify the data was inserted
SELECT 'User created successfully!' AS status;
SELECT id, username, email, created_at FROM users WHERE username = 'gabor';
SELECT COUNT(*) AS total_runs FROM runs WHERE user_id = @gabor_user_id;
