-- Import this file once into the same database used by Pinky Blog.
-- Login email: admin@pinkyblog.com
-- Password: SakuraAdmin!2026

INSERT INTO user (username, email, password, role)
VALUES (
    'pinky_admin',
    'admin@pinkyblog.com',
    '$2y$10$4WWjdZfBQWTyn2D4WBE82utHuX13nW11dhAAO1bexzrtBdhlZIRxy',
    'admin'
)
ON DUPLICATE KEY UPDATE
    password = VALUES(password),
    role = 'admin';
