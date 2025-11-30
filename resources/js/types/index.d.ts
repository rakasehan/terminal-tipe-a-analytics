export interface User {
    id: number;
    name: string;
    email: string;
    terminal: Terminal | null;
    roles: string[];
    permissions: string[];
    is_super_admin: boolean;
    is_terminal_admin: boolean;
}

export interface Terminal {
    id: number;
    code: string;
    name: string;
    type: 'A' | 'B' | 'C';
    address: string;
    city: string;
    province: string;
    latitude: number | null;
    longitude: number | null;
    capacity: number;
    boarding_gates: number;
    parking_slots: number;
    phone: string | null;
    email: string | null;
    status: 'active' | 'inactive' | 'maintenance';
    created_at: string;
    updated_at: string;
}

export interface Operator {
    id: number;
    code: string;
    name: string;
    address: string;
    phone: string;
    email: string | null;
    director_name: string | null;
    license_number: string | null;
    license_expiry: string | null;
    status: 'active' | 'inactive' | 'suspended';
}

export interface Route {
    id: number;
    operator_id: number;
    operator?: Operator;
    code: string;
    origin_city: string;
    destination_city: string;
    type: 'AKAP' | 'AKDP' | 'AJDP';
    distance: number;
    base_fare: number;
    estimated_duration: number;
    stops: string[] | null;
    status: 'active' | 'inactive';
    full_name: string;
}

export interface Vehicle {
    id: number;
    operator_id: number;
    operator?: Operator;
    plate_number: string;
    vehicle_type: string;
    brand: string | null;
    seat_capacity: number;
    year: number | null;
    condition: 'excellent' | 'good' | 'fair' | 'poor';
    last_maintenance: string | null;
    kir_expiry: string | null;
    status: 'active' | 'inactive' | 'maintenance';
}

export interface Departure {
    id: number;
    terminal_id: number;
    terminal?: Terminal;
    route_id: number;
    route?: Route;
    vehicle_id: number;
    vehicle?: Vehicle;
    operator_id: number;
    operator?: Operator;
    departure_date: string;
    scheduled_time: string;
    actual_time: string | null;
    passengers: number;
    seat_capacity: number;
    occupancy_rate: number;
    revenue: number | null;
    gate_number: string | null;
    status: 'scheduled' | 'departed' | 'cancelled' | 'delayed';
    notes: string | null;
    created_at: string;
    updated_at: string;
}

export interface PassengerStatistic {
    id: number;
    terminal_id: number;
    date: string;
    total_arrivals: number;
    total_departures: number;
    peak_hour_start: number | null;
    peak_hour_end: number | null;
    peak_hour_passengers: number | null;
    hourly_distribution: Record<number, number> | null;
    route_distribution: Record<string, number> | null;
    average_waiting_time: number | null;
}

export interface FinancialRecord {
    id: number;
    terminal_id: number;
    date: string;
    type: 'revenue' | 'expense';
    category: 'retribution' | 'parking' | 'commercial' | 'operational' | 'maintenance' | 'utilities' | 'salary' | 'other';
    description: string;
    amount: number;
    reference_number: string | null;
    created_at: string;
    updated_at: string;
}

export interface Statistics {
    total_departures: number;
    total_passengers: number;
    total_revenue: number;
    average_occupancy: number;
}

export interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

export interface PageProps {
    auth: {
        user: User | null;
    };
    flash: {
        success: string | null;
        error: string | null;
    };
    ziggy: {
        location: string;
    };
}