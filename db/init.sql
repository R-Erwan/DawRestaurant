-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users
(
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100), -- Optionnel
    phone_number VARCHAR(20), -- Optionnel
    created_at TIMESTAMP DEFAULT NOW()
);

-- Table des rôles
CREATE TABLE IF NOT EXISTS roles
(
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Table de liaison entre utilisateurs et rôles
CREATE TABLE IF NOT EXISTS user_roles
(
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role_id INT NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, role_id)
);

-- Table des réservations
CREATE TABLE IF NOT EXISTS reservations
(
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time VARCHAR(255) NOT NULL,
    number_of_people INT NOT NULL,
    status VARCHAR(255) DEFAULT 'waiting' CHECK (status IN ('waiting', 'confirmed', 'cancelled')),
    created_at TIMESTAMP DEFAULT NOW()
);


