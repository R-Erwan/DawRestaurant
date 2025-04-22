-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),  -- Optionnel
    phone_number VARCHAR(20),  -- Optionnel
    last_reset_request TIMESTAMPTZ,
    created_at TIMESTAMPTZ DEFAULT NOW()
    );

-- Table des rôles
CREATE TABLE IF NOT EXISTS roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
    );

-- Table de liaison entre utilisateurs et rôles
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role_id INT NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, role_id)
);

-- Table des annonces
CREATE TABLE IF NOT EXISTS announces (
    id SERIAL PRIMARY KEY,
    type INT NOT NULL,
    position INT NOT NULL DEFAULT 0,
    title VARCHAR(100),
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS days_rules (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    open bool NOT NULL DEFAULT true
);

CREATE TABLE IF NOT EXISTS time_rules (
    id SERIAL PRIMARY KEY,
    id_days INT NOT NULL REFERENCES days_rules(id) ON DELETE CASCADE,
    time_start TIME NOT NULL,
    time_end TIME NOT NULL,
    number_places INT NOT NULL,
    CHECK ( time_end > time_start ),
    UNIQUE (id_days, time_start, time_end)
);

CREATE TABLE IF NOT EXISTS exception_rules (
    id SERIAL PRIMARY KEY,
    date DATE NOT NULL UNIQUE,
    open BOOLEAN NOT NULL DEFAULT false,
    comment TEXT
);

CREATE TABLE IF NOT EXISTS exception_time_rules (
    id SERIAL PRIMARY KEY,
    id_exc INT NOT NULL REFERENCES exception_rules(id) ON DELETE CASCADE,
    time_start TIME NOT NULL,
    time_end TIME NOT NULL,
    number_of_places INT NOT NULL,
    CHECK (time_end > time_start),
    UNIQUE (id_exc, time_start, time_end)
);


-- Table des réservations
CREATE TABLE IF NOT EXISTS reservations
(
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    number_of_people INT NOT NULL,
    status VARCHAR(255) DEFAULT 'waiting' CHECK (status IN ('waiting', 'confirmed', 'cancelled')),
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Table des token de réinitialisation de mot de passe
CREATE TABLE IF NOT EXISTS password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMPTZ NOT NULL
);

--Tables pour le menu

CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS subcategories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS dishes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(5,2) NOT NULL,
    subcategory_id INT NOT NULL,
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id)
);