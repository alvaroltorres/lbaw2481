
-- Set the search path to the new schema
DROP SCHEMA IF EXISTS lbaw2481 CASCADE;
CREATE SCHEMA lbaw2481;
SET search_path TO lbaw2481;

-- ******************************
-- Create Tables
-- ******************************

-- 1. Category Table
CREATE TABLE "Category" (
                          category_id SERIAL PRIMARY KEY,
                          parent_id INTEGER REFERENCES "Category"(category_id) ON DELETE CASCADE,
                          name VARCHAR(255) UNIQUE NOT NULL
);

-- 2. User Table
CREATE TABLE "User" (
                        user_id SERIAL PRIMARY KEY,
                        is_blocked BOOLEAN NOT NULL,
                        is_admin BOOLEAN NOT NULL,
                        two_factor_enabled BOOLEAN NOT NULL DEFAULT FALSE,
                        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
                        updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
                        username VARCHAR(50) UNIQUE NOT NULL,
                        email VARCHAR(100) UNIQUE NOT NULL,
                        password_hash VARCHAR(255) NOT NULL,
                        fullname VARCHAR(100) NOT NULL,
                        nif VARCHAR(20) NOT NULL
);

-- 3. User Settings Table
CREATE TABLE UserSettings (
                              user_id INTEGER PRIMARY KEY REFERENCES "User"(user_id) ON DELETE CASCADE,
                              dark_mode_enabled BOOLEAN NOT NULL DEFAULT FALSE,
                              notifications_enabled BOOLEAN NOT NULL DEFAULT TRUE,
                              language_code VARCHAR(10) DEFAULT 'en'
);

-- 4. User Points Table
CREATE TABLE UserPoints (
                            user_id INTEGER PRIMARY KEY REFERENCES "User"(user_id) ON DELETE CASCADE,
                            points INTEGER NOT NULL DEFAULT 0
);

-- 5. Two-Factor Authentication Table
CREATE TABLE TwoFactor (
                           user_id INTEGER PRIMARY KEY REFERENCES "User"(user_id) ON DELETE CASCADE,
                           secret_key VARCHAR(255) NOT NULL,
                           is_enabled BOOLEAN NOT NULL DEFAULT FALSE
);

-- 6. Blocked User Table
CREATE TABLE BlockedUser (
                             blocked_user_id INTEGER PRIMARY KEY REFERENCES "User"(user_id) ON DELETE CASCADE,
                             admin_id INTEGER REFERENCES "User"(user_id),
                             blocked_at TIMESTAMP NOT NULL DEFAULT NOW(),
                             reason TEXT
);

-- 7. Address Table
CREATE TABLE Address (
                         address_id SERIAL PRIMARY KEY,
                         user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                         created_at TIMESTAMP NOT NULL DEFAULT NOW(),
                         updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
                         street VARCHAR(255) NOT NULL,
                         city VARCHAR(100) NOT NULL,
                         state_district VARCHAR(100),
                         postal_code VARCHAR(20),
                         country VARCHAR(100) NOT NULL
);

-- 8. Payment Method Table
CREATE TABLE PaymentMethod (
                               payment_method_id SERIAL PRIMARY KEY,
                               user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                               is_enterprise BOOLEAN NOT NULL,
                               expiry_date DATE NOT NULL,
                               created_at TIMESTAMP NOT NULL DEFAULT NOW(),
                               updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
                               card_number VARCHAR(20) NOT NULL,
                               ccv VARCHAR(4) NOT NULL,
                               fullname VARCHAR(100) NOT NULL,
                               nif VARCHAR(20) NOT NULL
);

-- 9. Auction Table
CREATE TABLE "Auction" (
                         auction_id SERIAL PRIMARY KEY,
                         user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                         category_id INTEGER REFERENCES "Category"(category_id) ON DELETE CASCADE,
                         starting_price DECIMAL(10, 2) NOT NULL CHECK (starting_price >= 0),
                         reserve_price DECIMAL(10, 2),
                         current_price DECIMAL(10, 2) CHECK (current_price >= 0),
                         minimum_bid_increment DECIMAL(10, 2) NOT NULL DEFAULT 1.00,
                         description TEXT,
                         starting_date TIMESTAMP NOT NULL,
                         ending_date TIMESTAMP NOT NULL,
                         created_at TIMESTAMP NOT NULL DEFAULT NOW(),
                         updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
                         title VARCHAR(255) NOT NULL,
                         location VARCHAR(255),
                         status VARCHAR(50) NOT NULL,
                         winner_id INTEGER REFERENCES "User"(user_id) ON DELETE SET NULL
);

-- 10. Auction Status History Table
CREATE TABLE AuctionStatusHistory (
                                      auction_status_history_id SERIAL PRIMARY KEY,
                                      auction_id INTEGER REFERENCES "Auction"(auction_id) ON DELETE CASCADE,
                                      status VARCHAR(50) NOT NULL,
                                      changed_at TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 11. Transaction Table
CREATE TABLE Transaction (
                             transaction_id SERIAL PRIMARY KEY,
                             buyer_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                             auction_id INTEGER REFERENCES "Auction"(auction_id) ON DELETE CASCADE,
                             payment_method_id INTEGER REFERENCES PaymentMethod(payment_method_id) ON DELETE SET NULL,
                             value DECIMAL(10, 2) NOT NULL,
                             created_at TIMESTAMP NOT NULL DEFAULT NOW(),
                             payment_deadline TIMESTAMP NOT NULL,
                             status VARCHAR(50) NOT NULL
);

-- 12. Payment Table
CREATE TABLE Payment (
                         payment_id SERIAL PRIMARY KEY,
                         transaction_id INTEGER REFERENCES Transaction(transaction_id) ON DELETE CASCADE,
                         payment_method_id INTEGER REFERENCES PaymentMethod(payment_method_id) ON DELETE SET NULL,
                         amount DECIMAL(10, 2) NOT NULL,
                         status VARCHAR(50) NOT NULL,
                         payment_date TIMESTAMP,
                         due_date TIMESTAMP NOT NULL,
                         created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 13. Rating Table
CREATE TABLE Rating (
                        rating_id SERIAL PRIMARY KEY,
                        rated_user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                        rater_user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                        transaction_id INTEGER REFERENCES Transaction(transaction_id) ON DELETE SET NULL,
                        score INTEGER NOT NULL CHECK (score >= 1 AND score <= 5),
                        comment TEXT,
                        rating_time TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 14. Bid Table
CREATE TABLE "Bid" (
                     bid_id SERIAL PRIMARY KEY,
                     auction_id INTEGER REFERENCES "Auction"(auction_id) ON DELETE CASCADE,
                     user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                     price DECIMAL(10, 2) NOT NULL CHECK (price > 0),
                     time TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 15. Watchlist Table
CREATE TABLE Watchlist (
                           watchlist_id SERIAL PRIMARY KEY,
                           user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                           auction_id INTEGER REFERENCES "Auction"(auction_id) ON DELETE CASCADE,
                           added_at TIMESTAMP NOT NULL DEFAULT NOW(),
                           UNIQUE (user_id, auction_id)
);

-- 16. Notification Table
CREATE TABLE notifications (
                               id SERIAL PRIMARY KEY,
                               type VARCHAR(255) NOT NULL,
                               notifiable_id INTEGER NOT NULL,
                               notifiable_type VARCHAR(255) NOT NULL,
                               data TEXT NOT NULL,
                               read_at TIMESTAMP NULL,
                               created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                               updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
);

-- 17. Chat Table
CREATE TABLE Chat (
                      chat_id SERIAL PRIMARY KEY,
                      auction_id INTEGER REFERENCES "Auction"(auction_id) ON DELETE CASCADE,
                      is_private BOOLEAN NOT NULL DEFAULT FALSE,
                      created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 18. Chat Participant Table
CREATE TABLE ChatParticipant (
                                 chat_participant_id SERIAL PRIMARY KEY,
                                 chat_id INTEGER REFERENCES Chat(chat_id) ON DELETE CASCADE,
                                 user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                                 joined_at TIMESTAMP NOT NULL DEFAULT NOW(),
                                 UNIQUE (chat_id, user_id)
);

-- 19. Message Table
CREATE TABLE Message (
                         message_id SERIAL PRIMARY KEY,
                         chat_id INTEGER REFERENCES Chat(chat_id) ON DELETE CASCADE,
                         sender_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                         text TEXT NOT NULL,
                         time TIMESTAMP NOT NULL DEFAULT NOW(),
                         auction_id INTEGER REFERENCES "Auction"(auction_id) ON DELETE CASCADE
);

-- 20. Report Table
CREATE TABLE Report (
                        report_id SERIAL PRIMARY KEY,
                        reported_user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                        reporter_user_id INTEGER REFERENCES "User"(user_id) ON DELETE CASCADE,
                        auction_id INTEGER REFERENCES "Auction"(auction_id),
                        content TEXT NOT NULL,
                        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
                        status VARCHAR(50) NOT NULL DEFAULT 'Pending',
                        admin_id INTEGER REFERENCES "User"(user_id)
);

-- 21. Orders Table (Renamed from "Order.php")
CREATE TABLE Orders (
                        order_id SERIAL PRIMARY KEY,
                        transaction_id INTEGER REFERENCES Transaction(transaction_id) ON DELETE CASCADE,
                        created_at TIMESTAMP NOT NULL DEFAULT NOW()
);
