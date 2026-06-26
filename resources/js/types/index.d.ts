export interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
    role: 'customer' | 'operator' | 'admin';
    address: string | null;
    is_active: boolean;
    email_verified_at?: string;
}

export interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    is_active: boolean;
    products_count?: number;
    created_at: string;
    updated_at: string;
}

export interface Product {
    id: number;
    category_id: number;
    name: string;
    slug: string;
    description: string | null;
    price: number | string;
    stock: number;
    image_path: string | null;
    is_active: boolean;
    category?: Category;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
}

export interface CartItem {
    id: number;
    cart_id: number;
    product_id: number;
    quantity: number;
    unit_price: number | string;
    subtotal: number | string;
    product?: Product;
    created_at: string;
    updated_at: string;
}

export interface Cart {
    id: number;
    user_id: number;
    items?: CartItem[];
    created_at: string;
    updated_at: string;
}


export interface OrderStatusHistory {
    id: number;
    order_id: number;
    previous_status: string | null;
    current_status: string;
    note: string | null;
    changed_by: number | null;
    actor?: User | null;
    created_at: string;
    updated_at: string;
}

export interface Payment {
    id: number;
    order_id: number;
    payment_method: string;
    destination_bank: string;
    sender_bank: string;
    account_holder_name: string;
    amount: number | string;
    transfer_date: string;
    proof_path: string;
    verification_status: 'pending' | 'waiting_verification' | 'verified' | 'rejected';
    verified_by: number | null;
    verified_at: string | null;
    rejection_note: string | null;
    verifier?: User | null;
    created_at: string;
    updated_at: string;
}

export interface OrderItem {
    id: number;
    order_id: number;
    product_id: number | null;
    product_name: string;
    unit_price: number | string;
    quantity: number;
    subtotal: number | string;
    product?: Product | null;
    created_at: string;
    updated_at: string;
}

export interface Order {
    id: number;
    order_number: string;
    user_id: number;
    order_date: string;
    delivery_date: string;
    recipient_name: string;
    recipient_phone: string;
    delivery_address: string;
    greeting_message: string | null;
    customer_note: string | null;
    operator_note: string | null;
    subtotal: number | string;
    delivery_fee: number | string;
    total: number | string;
    payment_status: 'pending' | 'waiting_verification' | 'verified' | 'rejected';
    order_status: 'pending_payment' | 'waiting_verification' | 'paid' | 'processing' | 'ready' | 'shipped' | 'completed' | 'cancelled' | 'rejected';
    cancelled_at: string | null;
    completed_at: string | null;
    user?: User;
    items?: OrderItem[];
    payment?: Payment | null;
    histories?: OrderStatusHistory[];
    created_at: string;
    updated_at: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    flash: {
        success: string | null;
        error: string | null;
        warning: string | null;
        info: string | null;
    };
};
