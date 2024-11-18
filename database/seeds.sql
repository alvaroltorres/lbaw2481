-- seeds.sql

-- Set the search path to the schema
SET search_path TO lbaw2481;

-- ***********************************
-- Data Population
-- ***********************************

-- 1. Populate Category Table
INSERT INTO Category (parent_id, name)
VALUES
    (NULL, 'Electronics'),
    (NULL, 'Home Appliances'),
    (1, 'Mobile Phones'),
    (1, 'Computers');

-- 2. Populate User Table
INSERT INTO "User" (is_enterprise, is_admin, two_factor_enabled, created_at, updated_at, username, email, password_hash, fullname, nif)
VALUES
    (FALSE, FALSE, FALSE, NOW(), NOW(), 'john_doe', 'john@example.com', '$2y$10$examplehash1', 'John Doe', '123456789'),
    (FALSE, TRUE, FALSE, NOW(), NOW(), 'admin', 'admin@example.com', '$2y$10$examplehash2', 'Admin User', '987654321'),
    (TRUE, FALSE, FALSE, NOW(), NOW(), 'enterprise_user', 'enterprise@example.com', '$2y$10$examplehash3', 'Enterprise Inc.', '555555555');

-- 3. Populate UserSettings Table
INSERT INTO UserSettings (user_id, dark_mode_enabled, notifications_enabled, language_code)
VALUES
    (1, TRUE, TRUE, 'en'),
    (2, FALSE, TRUE, 'en'),
    (3, TRUE, FALSE, 'pt');

-- 4. Populate UserPoints Table
INSERT INTO UserPoints (user_id, points)
VALUES
    (1, 100),
    (2, 200),
    (3, 150);

-- 5. Populate TwoFactor Table
INSERT INTO TwoFactor (user_id, secret_key, is_enabled)
VALUES
    (1, 'secretkey1', TRUE),
    (2, 'secretkey2', FALSE);

-- 6. Populate Address Table
INSERT INTO Address (user_id, street, city, state_district, postal_code, country)
VALUES
    (1, '123 Main St', 'New York', 'NY', '10001', 'USA'),
    (2, '456 Broad St', 'San Francisco', 'CA', '94105', 'USA'),
    (3, '789 Market St', 'Los Angeles', 'CA', '90001', 'USA');

-- 7. Populate PaymentMethod Table
INSERT INTO PaymentMethod (user_id, is_enterprise, expiry_date, card_number, ccv, fullname, nif)
VALUES
    (1, FALSE, '2025-12-31', '4111111111111111', '123', 'John Doe', '123456789'),
    (3, TRUE, '2026-06-30', '5555555555554444', '456', 'Enterprise Inc.', '555555555');

-- 8. Populate Auction Table
INSERT INTO Auction (user_id, category_id, starting_price, reserve_price, current_price, minimum_bid_increment, description, starting_date, ending_date, title, location, status)
VALUES
    (1, 3, 200.00, 300.00, 250.00, 10.00, 'iPhone 12 in good condition', NOW(), NOW() + INTERVAL '7 days', 'iPhone 12 Auction', 'New York, USA', 'Open'),
    (2, 4, 500.00, 700.00, NULL, 20.00, 'MacBook Pro 2020', NOW(), NOW() + INTERVAL '10 days', 'MacBook Auction', 'San Francisco, USA', 'Scheduled');

-- 9. Populate Bid Table
INSERT INTO Bid (auction_id, user_id, price)
VALUES
    (1, 2, 250.00),
    (1, 3, 260.00);

-- 10. Populate Watchlist Table
INSERT INTO Watchlist (user_id, auction_id)
VALUES
    (2, 1),
    (3, 1);

-- 11. Populate Notification Table
INSERT INTO Notification (user_id, content, type, auction_id, bid_id)
VALUES
    (1, 'Your auction has received a new bid!', 'Auction Update', 1, 1),
    (2, 'You have been outbid on an auction.', 'Bid Alert', 1, 2);

-- 12. Populate Chat Table
INSERT INTO Chat (is_private)
VALUES
    (TRUE),
    (FALSE);

-- 13. Populate ChatParticipant Table
INSERT INTO ChatParticipant (chat_id, user_id)
VALUES
    (1, 1),
    (1, 2),
    (2, 3);

-- 14. Populate Message Table
INSERT INTO Message (chat_id, sender_id, text, auction_id)
VALUES
    (1, 1, 'Hello, I am interested in your auction!', 1),
    (1, 2, 'Thank you for your interest!', 1);

-- 15. Populate Report Table
INSERT INTO Report (reported_user_id, reporter_user_id, auction_id, content, status)
VALUES
    (1, 2, 1, 'User is posting fraudulent items.', 'Pending');

-- 16. Populate Orders Table
INSERT INTO Orders (transaction_id)
VALUES
    (1);

-- 17. Populate Transaction Table
INSERT INTO Transaction (buyer_id, auction_id, payment_method_id, value, payment_deadline, status)
VALUES
    (2, 1, 1, 260.00, NOW() + INTERVAL '5 days', 'Pending');

-- 18. Populate Payment Table
INSERT INTO Payment (transaction_id, payment_method_id, amount, status, due_date)
VALUES
    (1, 1, 260.00, 'Pending', NOW() + INTERVAL '5 days');

-- 19. Populate Rating Table
INSERT INTO Rating (rated_user_id, rater_user_id, transaction_id, score, comment)
VALUES
    (1, 2, 1, 5, 'Great seller, item as described.');

-- 20. Populate AuctionStatusHistory Table
INSERT INTO AuctionStatusHistory (auction_id, status)
VALUES
    (1, 'Open'),
    (1, 'Closed');

-- 21. Populate UserPoints Table
-- (Already populated in step 4)

-- 22. Populate TwoFactor Table
-- (Already populated in step 5)

-- 23. Populate BlockedUser Table
INSERT INTO BlockedUser (blocked_user_id, admin_id, reason)
VALUES
    (3, 2, 'Violation of terms of service');

-- Add additional data as needed
