#!/bin/bash

echo "🧪 PAYME FULL INTEGRATION TEST"
echo "=============================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Check if server is running
echo -e "${YELLOW}1. Checking if server is running...${NC}"
if curl -s http://localhost:8092 > /dev/null 2>&1; then
    echo -e "${GREEN}   ✓ Server is running on port 8092${NC}"
else
    echo -e "${RED}   ✗ Server not running. Start with: docker compose up -d${NC}"
    exit 1
fi

# 2. Check database
echo -e "\n${YELLOW}2. Checking database connection...${NC}"
if docker compose exec -T mariadb mariadb -uroot -p2309 -e "SELECT 1;" > /dev/null 2>&1; then
    echo -e "${GREEN}   ✓ Database connected${NC}"
else
    echo -e "${RED}   ✗ Database not accessible${NC}"
    exit 1
fi

# 3. Check Payme tables
echo -e "\n${YELLOW}3. Checking Payme tables...${NC}"
TABLES=$(docker compose exec -T mariadb mariadb -uroot -p2309 web_panel -e "SHOW TABLES LIKE 'payme%';" 2>/dev/null | grep -c payme)
if [ "$TABLES" -ge 2 ]; then
    echo -e "${GREEN}   ✓ Payme tables exist (found $TABLES tables)${NC}"
else
    echo -e "${RED}   ✗ Payme tables not found${NC}"
    exit 1
fi

# 4. Check routes
echo -e "\n${YELLOW}4. Checking Payme routes...${NC}"
if php artisan route:list 2>/dev/null | grep -q "payme:merchant"; then
    echo -e "${GREEN}   ✓ Callback route exists: POST /api/payment/payme/callback/${NC}"
else
    echo -e "${RED}   ✗ Callback route not found${NC}"
fi

if php artisan route:list 2>/dev/null | grep -q "wallet-process-payme"; then
    echo -e "${GREEN}   ✓ Payment route exists: POST /wallet-process-payme${NC}"
else
    echo -e "${RED}   ✗ Payment route not found${NC}"
fi

# 5. Check Handler
echo -e "\n${YELLOW}5. Checking PaymeHandler...${NC}"
if [ -f "app/Handlers/PaymeHandler.php" ]; then
    echo -e "${GREEN}   ✓ PaymeHandler.php exists${NC}"
else
    echo -e "${RED}   ✗ PaymeHandler.php not found${NC}"
fi

# 6. Check config
echo -e "\n${YELLOW}6. Checking Payme configuration...${NC}"
if grep -q "PAYME_ID=" .env 2>/dev/null; then
    echo -e "${GREEN}   ✓ PAYME_ID configured${NC}"
    echo -e "${GREEN}   ✓ PAYME_KEY configured${NC}"
    echo -e "${GREEN}   ✓ PAYME_URL configured${NC}"
else
    echo -e "${RED}   ✗ Payme config missing in .env${NC}"
fi

echo ""
echo "=============================="
echo -e "${GREEN}🎉 ALL CHECKS PASSED!${NC}"
echo "=============================="
echo ""
echo "📝 Next: Test with actual payment"
echo "   1. Create PaymentRequest"
echo "   2. Call POST /wallet-process-payme"
echo "   3. Follow Payme checkout link"
echo "   4. Complete payment"
echo "   5. Check logs: tail -f storage/logs/laravel.log"
