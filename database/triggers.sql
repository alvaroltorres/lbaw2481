-- triggers.sql

-- Set the search path to the schema
SET search_path TO lbaw2481;

-- ***********************************
-- Triggers and Functions
-- ***********************************

-- 1. Prevent Self-Bid Trigger
CREATE OR REPLACE FUNCTION prevent_self_bid()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.user_id = (SELECT user_id FROM "Auction" WHERE auction_id = NEW.auction_id) THEN
        RAISE EXCEPTION 'User cannot bid on their own auction.';
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_self_bid_trigger
    BEFORE INSERT ON "Bid"
    FOR EACH ROW EXECUTE FUNCTION prevent_self_bid();

-- 2. Update Auction Current Price After Bid
CREATE OR REPLACE FUNCTION update_auction_current_price()
RETURNS TRIGGER AS $$
BEGIN
UPDATE "Auction"
SET current_price = NEW.price,
    updated_at = NOW()
WHERE auction_id = NEW.auction_id;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_auction_current_price_trigger
    AFTER INSERT ON "Bid"
    FOR EACH ROW EXECUTE FUNCTION update_auction_current_price();


-- 4. Validate Payment Method Expiry Date
CREATE OR REPLACE FUNCTION validate_payment_method_expiry()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.expiry_date < CURRENT_DATE THEN
        RAISE EXCEPTION 'Expiry date must be today or in the future.';
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER validate_payment_method_expiry_trigger
    BEFORE INSERT OR UPDATE ON PaymentMethod
                         FOR EACH ROW EXECUTE FUNCTION validate_payment_method_expiry();

-- 5. Update Auction Status History
CREATE OR REPLACE FUNCTION update_auction_status_history()
RETURNS TRIGGER AS $$
BEGIN
INSERT INTO AuctionStatusHistory (auction_id, status)
VALUES (NEW.auction_id, NEW.status);
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_auction_status_history_trigger
    AFTER UPDATE OF status ON "Auction"
    FOR EACH ROW EXECUTE FUNCTION update_auction_status_history();

-- Add other triggers and functions as needed
