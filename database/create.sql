DROP TABLE IF EXISTS lbaw2481.Message CASCADE;
DROP TABLE IF EXISTS lbaw2481.ChatParticipant CASCADE;
DROP TABLE IF EXISTS lbaw2481.Chat CASCADE;
DROP TABLE IF EXISTS lbaw2481.Bid CASCADE;
DROP TABLE IF EXISTS lbaw2481.Watchlist CASCADE;
DROP TABLE IF EXISTS lbaw2481."Order" CASCADE;
DROP TABLE IF EXISTS lbaw2481.Transaction CASCADE;
DROP TABLE IF EXISTS lbaw2481.Notification CASCADE;
DROP TABLE IF EXISTS lbaw2481.Rating CASCADE;
DROP TABLE IF EXISTS lbaw2481.Auction CASCADE;
DROP TABLE IF EXISTS lbaw2481.PaymentMethod CASCADE;
DROP TABLE IF EXISTS lbaw2481.Address CASCADE;
DROP TABLE IF EXISTS lbaw2481."User" CASCADE;
DROP TABLE IF EXISTS lbaw2481.Category CASCADE;



DROP SCHEMA IF EXISTS lbaw2481 CASCADE;

CREATE SCHEMA lbaw2481;

-- Cria a tabela Category
CREATE TABLE IF NOT EXISTS lbaw2481.Category (
    category_id SERIAL PRIMARY KEY,
    parent_id INTEGER REFERENCES lbaw2481.Category(category_id) ON DELETE CASCADE,
    name VARCHAR(255) UNIQUE NOT NULL
    );

-- Cria a tabela User
CREATE TABLE IF NOT EXISTS lbaw2481."User" (
    user_id SERIAL PRIMARY KEY,
    is_enterprise BOOLEAN NOT NULL,
    is_admin BOOLEAN NOT NULL,
    two_factor_enabled BOOLEAN NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    nif VARCHAR(20) NOT NULL
    );

-- Cria a tabela Address
CREATE TABLE IF NOT EXISTS lbaw2481.Address (
    address_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    street VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state_district VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100) NOT NULL
    );

-- Cria a tabela PaymentMethod
CREATE TABLE IF NOT EXISTS lbaw2481.PaymentMethod (
    payment_method_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    is_enterprise BOOLEAN NOT NULL,
    expiry_date DATE NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    card_number VARCHAR(20) NOT NULL,
    ccv VARCHAR(4) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    nif VARCHAR(20) NOT NULL
    );

-- Cria a tabela Auction
CREATE TABLE IF NOT EXISTS lbaw2481.Auction (
    auction_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    category_id INTEGER REFERENCES lbaw2481.Category(category_id) ON DELETE CASCADE,
    starting_price DECIMAL(10, 2) NOT NULL,
    reserve_price DECIMAL(10, 2),
    current_price DECIMAL(10, 2),
    description TEXT,
    starting_date TIMESTAMP NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    title VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    status VARCHAR(50) NOT NULL
    );

-- Cria a tabela Rating
CREATE TABLE IF NOT EXISTS lbaw2481.Rating (
    rating_id SERIAL PRIMARY KEY,
    rated_user_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    rater_user_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    score INTEGER CHECK (score >=1 AND score <=5) NOT NULL,
    comment TEXT,
    rating_time TIMESTAMP NOT NULL
    );

-- Cria a tabela Notification
CREATE TABLE IF NOT EXISTS lbaw2481.Notification (
    notification_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    is_read BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL,
    type VARCHAR(50) NOT NULL
    );

-- Cria a tabela Transaction
CREATE TABLE IF NOT EXISTS lbaw2481.Transaction (
    transaction_id SERIAL PRIMARY KEY,
    buyer_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    auction_id INTEGER REFERENCES lbaw2481.Auction(auction_id) ON DELETE CASCADE,
    payment_method_id INTEGER REFERENCES lbaw2481.PaymentMethod(payment_method_id) ON DELETE CASCADE,
    value DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP NOT NULL,
    payment_deadline TIMESTAMP NOT NULL,
    status VARCHAR(50) NOT NULL
    );

-- Cria a tabela Order
CREATE TABLE IF NOT EXISTS lbaw2481."Order" (
    order_id SERIAL PRIMARY KEY,
    transaction_id INTEGER REFERENCES lbaw2481.Transaction(transaction_id) ON DELETE CASCADE,
    created_at TIMESTAMP NOT NULL
    );

-- Cria a tabela Watchlist
CREATE TABLE IF NOT EXISTS lbaw2481.Watchlist (
    watchlist_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    auction_id INTEGER REFERENCES lbaw2481.Auction(auction_id) ON DELETE CASCADE,
    added_at TIMESTAMP NOT NULL,
    UNIQUE (user_id, auction_id)
    );

-- Cria a tabela Bid
CREATE TABLE IF NOT EXISTS lbaw2481.Bid (
    bid_id SERIAL PRIMARY KEY,
    auction_id INTEGER REFERENCES lbaw2481.Auction(auction_id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    price DECIMAL(10, 2) NOT NULL,
    time TIMESTAMP NOT NULL
    );

-- Cria a tabela Chat
CREATE TABLE IF NOT EXISTS lbaw2481.Chat (
    chat_id SERIAL PRIMARY KEY,
    is_private BOOLEAN NOT NULL,
    created_at TIMESTAMP NOT NULL
);

-- Cria a tabela ChatParticipant
CREATE TABLE IF NOT EXISTS lbaw2481.ChatParticipant (
    chat_participant_id SERIAL PRIMARY KEY,
    chat_id INTEGER REFERENCES lbaw2481.Chat(chat_id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    joined_at TIMESTAMP NOT NULL,
    UNIQUE (chat_id, user_id)
    );

-- Cria a tabela Message
CREATE TABLE IF NOT EXISTS lbaw2481.Message (
    message_id SERIAL PRIMARY KEY,
    chat_id INTEGER REFERENCES lbaw2481.Chat(chat_id) ON DELETE CASCADE,
    sender_id INTEGER REFERENCES lbaw2481."User"(user_id) ON DELETE CASCADE,
    text TEXT NOT NULL,
    time TIMESTAMP NOT NULL
    );