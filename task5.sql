SELECT users.username, users.email, SUM(orders.amount) AS total_spent
FROM users
JOIN orders ON users.id = orders.user_id
GROUP BY users.id
ORDER BY total_spent DESC;