SET search_path TO thingy;

-- Expanded Populate Category table
INSERT INTO lbaw2481.Category (parent_id, name)
VALUES
    (NULL, 'Electronics'),
    (NULL, 'Home Appliances'),
    (NULL, 'Books'),
    (NULL, 'Fashion'),
    (NULL, 'Sports & Outdoors'),
    (1, 'Mobile Phones'),
    (1, 'Laptops'),
    (1, 'Tablets'),
    (1, 'Cameras'),
    (2, 'Kitchen Appliances'),
    (2, 'Vacuum Cleaners'),
    (2, 'Air Conditioners'),
    (3, 'Fiction'),
    (3, 'Non-fiction'),
    (4, 'Mens Clothing'),
    (4, 'Womens Clothing'),
    (5, 'Exercise Equipment'),
    (5, 'Outdoor Gear'),
    (NULL, 'Musical Instruments'),
    (1, 'Wearables'),
    (1, 'Smart Home Devices'),
    (2, 'Refrigerators'),
    (2, 'Microwaves'),
    (3, 'Children Books'),
    (4, 'Accessories'),
    (5, 'Camping Equipment'),
    (5, 'Cycling'),
    (NULL, 'Groceries'),
    (NULL, 'Pet Supplies'),
    (NULL, 'Automotive');

-- Expanded Populate User table
INSERT INTO "users" (is_enterprise, is_admin, two_factor_enabled, created_at, updated_at, username, email, password_hash, fullname, nif)
VALUES
    (FALSE, FALSE, TRUE, NOW(), NOW(), 'john_doe', 'john@example.com', Hash::make('1234'), 'John Doe', '123456789'),
    (FALSE, TRUE, TRUE, NOW(), NOW(), 'admin', 'admin@example.com', Hash::make('1234'), 'Admin User', '987654321'),
    (TRUE, FALSE, FALSE, NOW(), NOW(), 'enterprise_user', 'enterprise@example.com', Hash::make('1234'), 'Enterprise Corp', '123123123'),
    (FALSE, FALSE, TRUE, NOW(), NOW(), 'jane_doe', 'jane@example.com', Hash::make('1234'), 'Jane Doe', '456456456'),
    (FALSE, FALSE, FALSE, NOW(), NOW(), 'charlie', 'charlie@example.com', Hash::make('1234'), 'Charlie Brown', '789789789'),
    (TRUE, FALSE, TRUE, NOW(), NOW(), 'tech_corp', 'techcorp@example.com', Hash::make('1234'), 'Tech Corporation', '741741741'),
    (FALSE, FALSE, TRUE, NOW(), NOW(), 'alice', 'alice@example.com', Hash::make('1234'), 'Alice Wonderland', '852852852'),
    (FALSE, FALSE, FALSE, NOW(), NOW(), 'bob', 'bob@example.com', Hash::make('1234'), 'Bob Builder', '963963963'),
    (TRUE, FALSE, FALSE, NOW(), NOW(), 'big_store', 'bigstore@example.com', Hash::make('1234'), 'Big Store Inc.', '159159159'),
    (FALSE, TRUE, TRUE, NOW(), NOW(), 'support_admin', 'support@example.com', Hash::make('1234'), 'Support Admin', '753753753'),
    (FALSE, FALSE, TRUE, NOW(), NOW(), 'eliza', 'eliza@example.com', Hash::make('1234'), 'Eliza Thornberry', '951753852'),
    (FALSE, FALSE, TRUE, NOW(), NOW(), 'mike', 'mike@example.com', Hash::make('1234'), 'Mike Wazowski', '654987321'),
    (TRUE, FALSE, TRUE, NOW(), NOW(), 'toy_shop', 'toyshop@example.com', Hash::make('1234'), 'Toy Shop LLC', '314159265');

-- Expanded Populate Address table
INSERT INTO lbaw2481.Address (user_id, created_at, updated_at, street, city, state_district, postal_code, country)
VALUES
    (1, NOW(), NOW(), '123 Main St', 'New York', 'NY', '10001', 'USA'),
    (2, NOW(), NOW(), '456 Broad St', 'San Francisco', 'CA', '94105', 'USA'),
    (3, NOW(), NOW(), '789 Oak Ave', 'Chicago', 'IL', '60610', 'USA'),
    (4, NOW(), NOW(), '321 Pine St', 'Houston', 'TX', '77002', 'USA'),
    (5, NOW(), NOW(), '654 Maple Rd', 'Los Angeles', 'CA', '90001', 'USA'),
    (6, NOW(), NOW(), '987 Birch Blvd', 'Seattle', 'WA', '98101', 'USA'),
    (7, NOW(), NOW(), '159 Cedar Ct', 'Boston', 'MA', '02108', 'USA'),
    (8, NOW(), NOW(), '753 Walnut Ln', 'Denver', 'CO', '80202', 'USA'),
    (9, NOW(), NOW(), '852 Spruce Dr', 'Atlanta', 'GA', '30303', 'USA'),
    (10, NOW(), NOW(), '963 Chestnut Pl', 'Miami', 'FL', '33101', 'USA');

-- Expanded Populate PaymentMethod table
INSERT INTO lbaw2481.PaymentMethod (user_id, is_enterprise, expiry_date, created_at, updated_at, card_number, ccv, fullname, nif)
VALUES
    (1, FALSE, '2025-12-31', NOW(), NOW(), '4111111111111111', '123', 'John Doe', '123456789'),
    (3, TRUE, '2026-06-30', NOW(), NOW(), '5555555555554444', '456', 'Enterprise Corp', '123123123'),
    (4, FALSE, '2024-08-31', NOW(), NOW(), '4012888888881881', '789', 'Jane Doe', '456456456'),
    (5, FALSE, '2025-11-30', NOW(), NOW(), '4222222222222', '101', 'Charlie Brown', '789789789'),
    (6, TRUE, '2027-03-31', NOW(), NOW(), '5105105105105100', '202', 'Tech Corporation', '741741741'),
    (7, FALSE, '2026-07-31', NOW(), NOW(), '4111111111111112', '303', 'Alice Wonderland', '852852852'),
    (8, FALSE, '2025-09-30', NOW(), NOW(), '4007000000027', '404', 'Bob Builder', '963963963'),
    (9, TRUE, '2028-01-31', NOW(), NOW(), '6011111111111117', '505', 'Big Store Inc.', '159159159'),
    (10, FALSE, '2024-12-31', NOW(), NOW(), '3530111333300000', '606', 'Support Admin', '753753753'),
    (2, FALSE, '2025-10-15', NOW(), NOW(), '3056930009020004', '700', 'Eliza Thornberry', '951753852'),
    (6, TRUE, '2025-12-12', NOW(), NOW(), '6011000990139424', '888', 'Tech Corporation', '741741741');

-- Expanded Populate Auction table
INSERT INTO lbaw2481.Auction (user_id, category_id, starting_price, reserve_price, current_price, description, starting_date, created_at, updated_at, title, location, status)
VALUES
    (1, 3, 200.00, 300.00, 250.00, 'iPhone 12 in good condition', '2024-10-01', NOW(), NOW(), 'iPhone 12 Auction', 'New York, USA', 'Open'),
    (2, 4, 500.00, 700.00, 550.00, 'MacBook Pro 2020', '2024-09-15', NOW(), NOW(), 'MacBook Auction', 'San Francisco, USA', 'Closed'),
    (3, 5, 100.00, 150.00, 120.00, 'KitchenAid Mixer', '2024-11-01', NOW(), NOW(), 'Mixer Auction', 'Chicago, USA', 'Open'),
    (4, 6, 50.00, 80.00, 70.00, 'Dyson Vacuum Cleaner', '2024-09-25', NOW(), NOW(), 'Vacuum Auction', 'Houston, USA', 'Open'),
    (5, 7, 20.00, 40.00, 35.00, 'Air Conditioner - Window Unit', '2024-10-05', NOW(), NOW(), 'AC Auction', 'Los Angeles, USA', 'Open'),
    (6, 8, 300.00, 500.00, 450.00, 'Canon DSLR Camera', '2024-09-20', NOW(), NOW(), 'Camera Auction', 'Seattle, USA', 'Closed'),
    (7, 9, 15.00, 30.00, 25.00, 'Fiction Book Set', '2024-11-10', NOW(), NOW(), 'Book Set Auction', 'Boston, USA', 'Open'),
    (8, 10, 40.00, 60.00, 55.00, 'Mens Leather Jacket', '2024-09-30', NOW(), NOW(), 'Jacket Auction', 'Denver, USA', 'Open'),
    (9, 11, 60.00, 100.00, 90.00, 'Treadmill - Hardly Used', '2024-10-12', NOW(), NOW(), 'Treadmill Auction', 'Atlanta, USA', 'Open'),
    (10, 12, 500.00, 800.00, 750.00, 'Camping Gear Set', '2024-11-15', NOW(), NOW(), 'Camping Auction', 'Miami, USA', 'Open');

-- Expanded Populate Rating table
INSERT INTO lbaw2481.Rating (rated_user_id, rater_user_id, score, comment, rating_time)
VALUES
    (1, 2, 5, 'Excellent seller!', NOW()),
    (2, 1, 4, 'Good buyer, but a bit slow to respond', NOW()),
    (3, 4, 5, 'Great experience!', NOW()),
    (4, 3, 3, 'Item was as described, but delivery was late', NOW()),
    (5, 6, 4, 'Smooth transaction', NOW()),
    (6, 5, 5, 'Very professional', NOW()),
    (7, 8, 2, 'Item not as described', NOW()),
    (8, 7, 3, 'Average experience', NOW()),
    (9, 10, 5, 'Highly recommended!', NOW()),
    (10, 9, 4, 'Would buy again', NOW());

-- Expanded Populate Notification table
INSERT INTO lbaw2481.Notification (user_id, content, is_read, created_at, type)
VALUES
    (1, 'Your auction has received a new bid!', FALSE, NOW(), 'Auction Update'),
    (2, 'You have a new message from a buyer', FALSE, NOW(), 'Message'),
    (3, 'Your auction has been approved', FALSE, NOW(), 'Auction Update'),
    (4, 'Your bid was successful!', FALSE, NOW(), 'Bid Update'),
    (5, 'Payment for your order is due soon', FALSE, NOW(), 'Payment Reminder'),
    (6, 'Your auction has received a new bid!', FALSE, NOW(), 'Auction Update'),
    (7, 'Your item has been shipped', FALSE, NOW(), 'Shipment Update'),
    (8, 'New rating received on your profile', FALSE, NOW(), 'Rating Update'),
    (9, 'Auction closing soon!', FALSE, NOW(), 'Auction Update'),
    (10, 'Your bid was outbid by another user', FALSE, NOW(), 'Bid Update');

-- Expanded Populate Transaction table
INSERT INTO lbaw2481.Transaction (buyer_id, auction_id, payment_method_id, value, created_at, payment_deadline, status)
VALUES
    (1, 1, 1, 250.00, NOW(), '2024-10-15', 'Completed'),
    (2, 2, 2, 550.00, NOW(), '2024-10-20', 'Pending'),
    (4, 3, 3, 120.00, NOW(), '2024-11-05', 'Completed'),
    (5, 4, 4, 70.00, NOW(), '2024-10-10', 'Pending'),
    (6, 5, 5, 35.00, NOW(), '2024-10-20', 'Completed'),
    (7, 6, 6, 450.00, NOW(), '2024-11-01', 'Pending'),
    (8, 7, 7, 25.00, NOW(), '2024-11-15', 'Completed'),
    (9, 8, 8, 55.00, NOW(), '2024-10-25', 'Pending'),
    (10, 9, 9, 90.00, NOW(), '2024-11-20', 'Completed'),
    (3, 10, 10, 750.00, NOW(), '2024-11-30', 'Pending');

-- Expanded Populate Order table
INSERT INTO lbaw2481."Order" (transaction_id, created_at)
VALUES
    (1, NOW()),
    (2, NOW()),
    (3, NOW()),
    (4, NOW()),
    (5, NOW()),
    (6, NOW()),
    (7, NOW()),
    (8, NOW()),
    (9, NOW()),
    (10, NOW());

-- Expanded Populate Watchlist table
INSERT INTO lbaw2481.Watchlist (user_id, auction_id, added_at)
VALUES
    (1, 1, NOW()),
    (2, 1, NOW()),
    (3, 2, NOW()),
    (4, 3, NOW()),
    (5, 4, NOW()),
    (6, 5, NOW()),
    (7, 6, NOW()),
    (8, 7, NOW()),
    (9, 8, NOW()),
    (10, 9, NOW()),
    (1, 10, NOW());

-- Expanded Populate Bid table
INSERT INTO lbaw2481.Bid (auction_id, user_id, price, time)
VALUES
    (1, 1, 250.00, NOW()),
    (2, 2, 550.00, NOW()),
    (3, 4, 130.00, NOW()),
    (4, 5, 75.00, NOW()),
    (5, 6, 40.00, NOW()),
    (6, 7, 460.00, NOW()),
    (7, 8, 30.00, NOW()),
    (8, 9, 60.00, NOW()),
    (9, 10, 100.00, NOW()),
    (10, 3, 760.00, NOW());

-- Expanded Populate Chat table
INSERT INTO lbaw2481.Chat (is_private, created_at)
VALUES
    (TRUE, NOW()),
    (FALSE, NOW()),
    (TRUE, NOW()),
    (FALSE, NOW()),
    (TRUE, NOW()),
    (FALSE, NOW()),
    (TRUE, NOW()),
    (FALSE, NOW()),
    (TRUE, NOW()),
    (FALSE, NOW());

-- Expanded Populate ChatParticipant table
INSERT INTO lbaw2481.ChatParticipant (chat_id, user_id, joined_at)
VALUES
    (1, 1, NOW()),
    (2, 2, NOW()),
    (3, 3, NOW()),
    (4, 4, NOW()),
    (5, 5, NOW()),
    (6, 6, NOW()),
    (7, 7, NOW()),
    (8, 8, NOW()),
    (9, 9, NOW()),
    (10, 10, NOW());

-- Expanded Populate Message table
INSERT INTO lbaw2481.Message (chat_id, sender_id, text, time)
VALUES
    (1, 1, 'Hello, I am interested in your auction!', NOW()),
    (2, 2, 'Thank you for your interest!', NOW()),
    (3, 3, 'Can you provide more details about the product?', NOW()),
    (4, 4, 'Sure, here are the details...', NOW()),
    (5, 5, 'Is the item still available?', NOW()),
    (6, 6, 'Yes, it is available.', NOW()),
    (7, 7, 'What is the current highest bid?', NOW()),
    (8, 8, 'The current highest bid is $30.', NOW()),
    (9, 9, 'I would like to place a bid.', NOW()),
    (10, 10, 'Your bid has been recorded.', NOW());
