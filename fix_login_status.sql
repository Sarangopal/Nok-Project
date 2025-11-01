-- Update existing approved members
UPDATE registrations 
SET login_status = 'approved' 
WHERE card_issued_at IS NOT NULL;

-- Update pending members
UPDATE registrations 
SET login_status = 'pending' 
WHERE card_issued_at IS NULL;

-- Show results
SELECT id, memberName, email, login_status, renewal_status, card_issued_at 
FROM registrations 
ORDER BY id;

