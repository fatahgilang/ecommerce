/**
 * Utility functions for cart management
 */

/**
 * Clear cart and force reload from localStorage
 */
export const clearAndReloadCart = () => {
    localStorage.removeItem('cart');
    window.location.reload();
};

/**
 * Fix cart items with incorrect discount data
 */
export const fixCartDiscountData = () => {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    let hasChanges = false;
    
    const fixedCart = cart.map(item => {
        const originalItem = { ...item };
        
        // Fix items where discount data is inconsistent
        if (item.has_discount) {
            // If original_price is missing or incorrect
            if (!item.original_price || item.original_price <= item.product_price) {
                // Estimate original price based on common discount percentages
                const estimatedOriginal = item.product_price * 1.25; // Assume 20% discount
                item.original_price = estimatedOriginal;
                hasChanges = true;
            }
        } else {
            // If no discount, ensure original_price is not set
            if (item.original_price && item.original_price !== item.product_price) {
                item.original_price = item.product_price;
                hasChanges = true;
            }
        }
        
        // Ensure boolean type for has_discount
        if (typeof item.has_discount !== 'boolean') {
            item.has_discount = Boolean(item.has_discount);
            hasChanges = true;
        }
        
        return item;
    });
    
    if (hasChanges) {
        localStorage.setItem('cart', JSON.stringify(fixedCart));
        console.log('Cart discount data has been fixed');
    }
    
    return fixedCart;
};

/**
 * Validate cart item discount data
 */
export const validateCartItem = (item) => {
    const issues = [];
    
    if (item.has_discount) {
        if (!item.original_price) {
            issues.push('Missing original_price for discounted item');
        } else if (item.original_price <= item.product_price) {
            issues.push('Original price should be higher than discounted price');
        }
    }
    
    if (typeof item.has_discount !== 'boolean') {
        issues.push('has_discount should be boolean');
    }
    
    return {
        isValid: issues.length === 0,
        issues: issues
    };
};

/**
 * Get cart statistics
 */
export const getCartStats = () => {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    const stats = {
        totalItems: cart.length,
        totalQuantity: cart.reduce((sum, item) => sum + item.quantity, 0),
        discountedItems: cart.filter(item => item.has_discount).length,
        totalSavings: cart.reduce((sum, item) => {
            if (item.has_discount && item.original_price > item.product_price) {
                return sum + ((item.original_price - item.product_price) * item.quantity);
            }
            return sum;
        }, 0)
    };
    
    return stats;
};