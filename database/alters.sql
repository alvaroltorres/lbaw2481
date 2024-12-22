SET search_path TO lbaw2481;

ALTER TABLE "User"
    ADD COLUMN remember_token VARCHAR(100) NULL;


CREATE TABLE follow_auctions (
                                 id SERIAL PRIMARY KEY,
                                 user_id INTEGER NOT NULL,
                                 auction_id INTEGER NOT NULL,
                                 created_at TIMESTAMP NULL,
                                 updated_at TIMESTAMP NULL,
                                 FOREIGN KEY (user_id) REFERENCES "User"(user_id) ON DELETE CASCADE,
                                 FOREIGN KEY (auction_id) REFERENCES "Auction"(auction_id) ON DELETE CASCADE
);


ALTER TABLE "User"
    ADD COLUMN credits NUMERIC(8, 2) DEFAULT 0.00;



-- Add foreign key constraint to 'winner_id' referencing the 'id' column in the 'users' table
ALTER TABLE "Auction"
    ADD CONSTRAINT fk_winner_id FOREIGN KEY (winner_id) REFERENCES "User"(user_id) ON DELETE SET NULL;

-- Step 1: Drop the existing foreign key constraint
ALTER TABLE report
DROP CONSTRAINT IF EXISTS report_auction_id_fkey;

-- Step 2: Add the foreign key constraint with 'ON DELETE CASCADE'
ALTER TABLE report
    ADD CONSTRAINT fk_auction_id FOREIGN KEY (auction_id) REFERENCES "Auction" (auction_id) ON DELETE CASCADE;


-- Add 'image' column to the 'Auction' table
ALTER TABLE "Auction"
    ADD COLUMN image VARCHAR(255) NULL;

