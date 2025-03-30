-- Lister utilisateurs avec leur rôle
SELECT u.id, u.name, u.email, r.name AS role
FROM users u
         JOIN user_roles ur ON u.id = ur.user_id
         JOIN roles r ON ur.role_id = r.id;

-- Récupérer les roles d'un utilisateur spécifique
SELECT r.name
FROM roles r
         JOIN user_roles ur ON r.id = ur.role_id
WHERE ur.user_id = 1;

-- Vérifier si un utilisateur est admin
SELECT COUNT(*)
FROM user_roles ur
         JOIN roles r ON ur.role_id = r.id
WHERE ur.user_id = 1 AND r.name = 'admin';
