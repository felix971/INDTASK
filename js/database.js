// 模拟数据库功能
class Database {
    static initializeDemoData() {
        // 初始化用户数据
        if (!localStorage.getItem('users')) {
            const users = [
                { id: 1, username: 'admin', password: 'admin123', role: 'admin' },
                { id: 2, username: 'owner1', password: 'owner123', role: 'owner' },
                { id: 3, username: 'tenant1', password: 'tenant123', role: 'tenant' }
            ];
            localStorage.setItem('users', JSON.stringify(users));
        }
        
        // 初始化住户数据
        if (!localStorage.getItem('residents')) {
            const residents = [
                { id: 1, unit_number: '101', owner_name: '张三', contact_email: 'zhang.san@email.com', contact_phone: '0412345678', unit_entitlements: 0.0834 },
                { id: 2, unit_number: '102', owner_name: '李四', contact_email: 'li.si@email.com', contact_phone: '0423456789', unit_entitlements: 0.0834 },
                { id: 3, unit_number: '103', owner_name: '王五', contact_email: 'wang.wu@email.com', contact_phone: '0434567890', unit_entitlements: 0.0834 }
            ];
            localStorage.setItem('residents', JSON.stringify(residents));
        }
        
        // 初始化维护请求
        if (!localStorage.getItem('maintenance_requests')) {
            const requests = [
                { id: 1, unit_number: '101', description: '厨房水龙头漏水，需要维修', priority: 'medium', status: 'pending', created_at: new Date().toISOString() },
                { id: 2, unit_number: '102', description: '空调不制冷，可能需要加氟', priority: 'high', status: 'in_progress', created_at: new Date().toISOString() }
            ];
            localStorage.setItem('maintenance_requests', JSON.stringify(requests));
        }
        
        // 初始化费用记录
        if (!localStorage.getItem('levies')) {
            const levies = [
                { id: 1, unit_number: '101', amount: 850.00, due_date: '2025-07-01', status: 'pending' },
                { id: 2, unit_number: '102', amount: 850.00, due_date: '2025-07-01', status: 'paid' },
                { id: 3, unit_number: '103', amount: 850.00, due_date: '2025-06-01', status: 'overdue' }
            ];
            localStorage.setItem('levies', JSON.stringify(levies));
        }
        
        // 初始化文档
        if (!localStorage.getItem('documents')) {
            const documents = [
                { id: 1, title: '2025年度保险证书', description: '建筑物综合保险证书', file_path: 'https://example.com/insurance.pdf', document_type: 'insurance' },
                { id: 2, title: '2024年度财务报告', description: '年度收支和预算执行情况', file_path: 'https://example.com/financial.pdf', document_type: 'financial' }
            ];
            localStorage.setItem('documents', JSON.stringify(documents));
        }
    }
    
    static getUsers() {
        return JSON.parse(localStorage.getItem('users') || '[]');
    }
    
    static getResidents() {
        return JSON.parse(localStorage.getItem('residents') || '[]');
    }
    
    static addResident(resident) {
        const residents = this.getResidents();
        resident.id = Date.now();
        residents.push(resident);
        localStorage.setItem('residents', JSON.stringify(residents));
        return resident;
    }
    
    static getMaintenanceRequests() {
        return JSON.parse(localStorage.getItem('maintenance_requests') || '[]');
    }
    
    static addMaintenanceRequest(request) {
        const requests = this.getMaintenanceRequests();
        request.id = Date.now();
        request.created_at = new Date().toISOString();
        requests.push(request);
        localStorage.setItem('maintenance_requests', JSON.stringify(requests));
        return request;
    }
    
    static updateMaintenanceStatus(id, status) {
        const requests = this.getMaintenanceRequests();
        const request = requests.find(r => r.id == id);
        if (request) {
            request.status = status;
            localStorage.setItem('maintenance_requests', JSON.stringify(requests));
        }
    }
    
    static getLevies() {
        return JSON.parse(localStorage.getItem('levies') || '[]');
    }
    
    static addLevy(levy) {
        const levies = this.getLevies();
        levy.id = Date.now();
        levies.push(levy);
        localStorage.setItem('levies', JSON.stringify(levies));
        return levy;
    }
    
    static getDocuments() {
        return JSON.parse(localStorage.getItem('documents') || '[]');
    }
    
    static addDocument(document) {
        const documents = this.getDocuments();
        document.id = Date.now();
        document.uploaded_at = new Date().toISOString();
        documents.push(document);
        localStorage.setItem('documents', JSON.stringify(documents));
        return document;
    }
}