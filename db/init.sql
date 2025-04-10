-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),  -- Optionnel
    phone_number VARCHAR(20),  -- Optionnel
    created_at TIMESTAMP DEFAULT NOW()
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

CREATE TABLE IF NOT EXISTS announces (
    id SERIAL PRIMARY KEY,
    type INT NOT NULL,
    position INT NOT NULL DEFAULT 0,
    title VARCHAR(100),
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
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
