-- Set the search path to the new schema
SET search_path TO lbaw2481;

-- ******************************
-- 1. Category Table
-- ******************************

-- Insert Parent Categories
INSERT INTO "Category" (parent_id, name) VALUES
                                           (NULL, 'Electronics'),
                                           (NULL, 'Art'),
                                           (NULL, 'Fashion'),
                                           (NULL, 'Home & Garden'),
                                           (NULL, 'Vehicles'),
                                           (1, 'Mobile Phones'),
                                           (1, 'Laptops'),
                                           (2, 'Paintings'),
                                           (2, 'Sculptures'),
                                           (3, 'Men s Clothing'),
                                           (3, 'Women s Clothing'),
                                           (4, 'Furniture'),
                                           (4, 'Kitchen Appliances'),
                                           (5, 'Cars'),
                                           (5, 'Motorcycles');

-- ******************************
-- 2. User Table
-- ******************************

-- Insert Users
-- ALL PASSWORDS ARE 1234
INSERT INTO "User" (is_enterprise, is_admin, two_factor_enabled, username, email, password_hash, fullname, nif) VALUES
                                                                                                                    (FALSE, TRUE, FALSE, 'adminuser', 'admin@bidzenith.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Alice Admin', '123456789'),
                                                                                                                    (FALSE, FALSE, TRUE, 'johndoe', 'john.doe@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'John Doe', '987654321'),
                                                                                                                    (FALSE, FALSE, TRUE, 'janedoe', 'jane.doe@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Jane Doe', '192837465'),
                                                                                                                    (TRUE, FALSE, FALSE, 'enterprise1', 'contact@enterprise1.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'Enterprise One Ltd.', '564738291');

-- ******************************
-- 3. UserSettings Table
-- ******************************

INSERT INTO UserSettings (user_id, dark_mode_enabled, notifications_enabled, language_code) VALUES
                                                                                                (1, TRUE, TRUE, 'en'),
                                                                                                (2, FALSE, TRUE, 'en'),
                                                                                                (3, TRUE, FALSE, 'en'),
                                                                                                (4, FALSE, TRUE, 'en');

-- ******************************
-- 4. UserPoints Table
-- ******************************

INSERT INTO UserPoints (user_id, points) VALUES
                                             (1, 1000),
                                             (2, 250),
                                             (3, 500),
                                             (4, 1500);

-- ******************************
-- 5. TwoFactor Table
-- ******************************

INSERT INTO TwoFactor (user_id, secret_key, is_enabled) VALUES
                                                            (1, 'SECRETKEYADMIN', FALSE),
                                                            (2, 'SECRETKEYJOHN', TRUE),
                                                            (3, 'SECRETKEYJANE', TRUE),
                                                            (4, 'SECRETKEYENT1', FALSE);

-- ******************************
-- 6. Address Table
-- ******************************

INSERT INTO Address (user_id, street, city, state_district, postal_code, country) VALUES
                                                                                      (1, '123 Admin St', 'Admin City', 'Admin State', '12345', 'USA'),
                                                                                      (2, '456 John Ave', 'John Town', 'John State', '67890', 'USA'),
                                                                                      (3, '789 Jane Blvd', 'Jane Ville', 'Jane State', '13579', 'USA'),
                                                                                      (4, '321 Enterprise Rd', 'Enterprise City', 'Enterprise State', '24680', 'USA');

-- ******************************
-- 7. PaymentMethod Table
-- ******************************

INSERT INTO PaymentMethod (user_id, is_enterprise, expiry_date, card_number, ccv, fullname, nif) VALUES
                                                                                                     (1, FALSE, '2025-12-31', '4111111111111111', '123', 'Alice Admin', '123456789'),
                                                                                                     (2, FALSE, '2025-06-30', '4222222222222', '456', 'John Doe', '987654321'),
                                                                                                     (3, FALSE, '2025-09-15', '4333333333333333', '789', 'Jane Doe', '192837465'),
                                                                                                     (4, TRUE, '2026-01-01', '4444444444444448', '321', 'Enterprise One Ltd.', '564738291');

-- ******************************
-- 8. Auction Table
-- ******************************

INSERT INTO "Auction" (user_id, category_id, starting_price, reserve_price, current_price, minimum_bid_increment, description, starting_date, ending_date, title, location, status) VALUES
                                                                                                                                                                                      (2, 1, 100.00, 500.00, 150.00, 10.00, 'Latest smartphone model.', NOW() - INTERVAL '1 hour', NOW() + INTERVAL '2 hours', 'Smartphone Auction', 'New York, NY', 'Active'),
                                                                                                                                                                                      (3, 3, 200.00, 800.00, 300.00, 20.00, 'Designer handbag collection.', NOW() - INTERVAL '2 hours', NOW() + INTERVAL '3 hours', 'Handbag Auction', 'Los Angeles, CA', 'Active'),
                                                                                                                                                                                      (2, 2, 500.00, 1500.00, 700.00, 50.00, 'Original painting by famous artist.', NOW() + INTERVAL '5 hours', NOW() + INTERVAL '10 hours', 'Painting Auction', 'Paris, France', 'Upcoming'),
                                                                                                                                                                                      (3, 5, 1000.00, 5000.00, 1200.00, 100.00, 'Vintage motorcycle.', NOW() + INTERVAL '1 day', NOW() + INTERVAL '2 days', 'Motorcycle Auction', 'Chicago, IL', 'Upcoming'),
                                                                                                                                                                                      (4, 4, 300.00, 1200.00, 450.00, 30.00, 'Modern kitchen appliances set.', NOW() - INTERVAL '3 hours', NOW() + INTERVAL '1 hour', 'Kitchen Appliances Auction', 'Houston, TX', 'Active');

-- ******************************
-- 9. AuctionStatusHistory Table
-- ******************************

INSERT INTO AuctionStatusHistory (auction_id, status) VALUES
                                                          (1, 'Active'),
                                                          (2, 'Active'),
                                                          (3, 'Upcoming'),
                                                          (4, 'Upcoming'),
                                                          (5, 'Active');

-- ******************************
-- 10. Transaction Table
-- ******************************

INSERT INTO Transaction (buyer_id, auction_id, payment_method_id, value, payment_deadline, status) VALUES
                                                                                                       (2, 1, 2, 150.00, NOW() + INTERVAL '1 day', 'Pending'),
                                                                                                       (3, 2, 3, 300.00, NOW() + INTERVAL '2 days', 'Completed'),
                                                                                                       (2, 5, 2, 450.00, NOW() + INTERVAL '3 days', 'Pending');

-- ******************************
-- 11. Payment Table
-- ******************************

INSERT INTO Payment (transaction_id, payment_method_id, amount, status, payment_date, due_date) VALUES
                                                                                                    (1, 2, 150.00, 'Pending', NULL, NOW() + INTERVAL '1 day'),
                                                                                                    (2, 3, 300.00, 'Completed', NOW() - INTERVAL '1 hour', NOW() + INTERVAL '1 day'),
                                                                                                    (3, 2, 450.00, 'Pending', NULL, NOW() + INTERVAL '3 days');

-- ******************************
-- 12. Rating Table
-- ******************************

INSERT INTO Rating (rated_user_id, rater_user_id, transaction_id, score, comment) VALUES
                                                                                      (1, 2, 1, 5, 'Great seller! Fast transaction.'),
                                                                                      (3, 2, 2, 4, 'Good quality items.'),
                                                                                      (1, 3, 3, 5, 'Excellent buyer!');

-- ******************************
-- 13. Bid Table
-- ******************************

INSERT INTO "Bid" (auction_id, user_id, price) VALUES
                                                 (1, 3, 150.00),
                                                 (1, 4, 160.00),
                                                 (2, 4, 300.00),
                                                 (5, 2, 450.00);

-- ******************************
-- 14. Watchlist Table
-- ******************************

INSERT INTO Watchlist (user_id, auction_id) VALUES
                                                (2, 1),
                                                (2, 2),
                                                (3, 3),
                                                (4, 5);

-- ******************************
-- 15. Notification Table
-- ******************************

INSERT INTO Notification (user_id, content, type, auction_id, bid_id) VALUES
                                                                          (2, 'Your bid has been outbid on Smartphone Auction.', 'Bid', 1, 3),
                                                                          (3, 'Congratulations! You won the Handbag Auction.', 'Win', 2, 4),
                                                                          (2, 'Payment pending for Kitchen Appliances Auction.', 'Payment', 5, 2);

-- ******************************
-- 16. Chat Table
-- ******************************

INSERT INTO Chat (is_private) VALUES
                                  (FALSE),
                                  (TRUE);

-- ******************************
-- 17. ChatParticipant Table
-- ******************************

INSERT INTO ChatParticipant (chat_id, user_id) VALUES
                                                   (1, 2),
                                                   (1, 3),
                                                   (2, 1),
                                                   (2, 4);

-- ******************************
-- 18. Message Table
-- ******************************

INSERT INTO Message (chat_id, sender_id, text, auction_id) VALUES
                                                               (1, 2, 'Hello, is the smartphone still available?', 1),
                                                               (1, 1, 'Yes, it is still available.', 1),
                                                               (2, 1, 'Welcome to the Enterprise One support chat!', NULL),
                                                               (2, 4, 'Thank you!', NULL);

-- ******************************
-- 19. Report Table
-- ******************************

INSERT INTO Report (reported_user_id, reporter_user_id, auction_id, content, admin_id) VALUES
                                                                                           (2, 3, 1, 'User made inappropriate comments.', 1),
                                                                                           (3, 2, 2, 'User failed to complete the transaction.', 1);

-- ******************************
-- 20. BlockedUser Table
-- ******************************

INSERT INTO BlockedUser (blocked_user_id, admin_id, reason) VALUES
    (3, 1, 'Violation of terms of service.');

-- ******************************
-- 21. Orders Table
-- ******************************

INSERT INTO Orders (transaction_id) VALUES
                                        (1),
                                        (2),
                                        (3);
