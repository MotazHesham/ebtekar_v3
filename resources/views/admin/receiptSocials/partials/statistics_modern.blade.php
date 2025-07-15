@if(Gate::allows('statistics_receipts'))
<!-- Modern Statistics Dashboard -->
<div class="modern-stats-dashboard" id="modernStatsDashboard">
    <!-- Collapse Toggle Button -->
    <div class="stats-toggle-header" onclick="toggleModernStats()">
        <div class="toggle-content">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-chart-bar me-2"></i>
                {{ __('global.statistics') }} {{ __('cruds.receiptSocial.title') }}
            </h4> 
        </div>
        <div class="toggle-icon">
            <i class="fas fa-chevron-down text-dark" id="modernStatsToggleIcon"></i>
        </div>
    </div>
    
    <!-- Collapsible Content -->
    <div class="stats-content" id="modernStatsContent">
    <!-- Stats Overview Cards -->
    <div class="stats-overview">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="fas fa-list-ol"></i>
            </div>
            <div class="stat-content">
                <h3>{{ number_format($receipts->total()) }}</h3>
                <p>إجمالي الفواتير</p> 
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-content">
                <h3>{{ dashboard_currency(number_format(round($statistics['total_deposit']))) }}</h3>
                <p>إجمالي العربون</p> 
            </div>
        </div>

        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-content">
                <h3>{{ dashboard_currency(number_format(round($statistics['total_total_cost']))) }}</h3>
                <p>المجموع بالعربون</p> 
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="stat-content">
                <h3>{{ dashboard_currency(number_format(round($statistics['total_total_cost_without_deposit']))) }}</h3>
                <p>المجموع بدون العربون</p> 
            </div>
        </div>

        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <div class="stat-content">
                <h3>{{ dashboard_currency(number_format(round($statistics['total_shipping_country_cost']))) }}</h3>
                <p>إجمالي الشحن</p> 
            </div>
        </div>

        <div class="stat-card dark">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3>{{ dashboard_currency(number_format(round($statistics['total_grand_total']))) }}</h3>
                <p>المجموع الصافي</p> 
            </div>
        </div>
    </div> 

    <!-- Quick Actions -->
    <div class="quick-actions">
        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="action-content">
                <h6>تقرير العملاء</h6>
                <p>تصدير بيانات العملاء</p>
                <a href="{{ route('admin.receipt-socials.customer-report') }}" class="btn btn-sm btn-primary">
                    تصدير Excel
                </a>
            </div>
        </div>

        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="action-content">
                <h6>رسم بياني</h6>
                <p>تحليل توزيع العملاء</p>
                <button class="btn btn-sm btn-primary" onclick="showCustomerChart()">
                    عرض الرسم البياني
                </button>
            </div>
        </div>

        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="action-content">
                <h6>التصميم القديم</h6>
                <p>العودة للتصميم السابق</p>
                <a href="{{ route('admin.receipt-socials.index',['new_design' => false]) }}" class="btn btn-sm btn-warning">
                    عرض التصميم القديم
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Customer Chart Modal -->
<div class="modal fade" id="customerChartModal" tabindex="-1" aria-labelledby="customerChartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="customerChartModalLabel">
                    <i class="fas fa-chart-pie me-2"></i>
                    توزيع العملاء حسب عدد الطلبات
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <canvas id="customerChart"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
.modern-stats-dashboard { 
    border-radius: 20px;
    margin-bottom: 2rem;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    overflow: hidden;
}

.stats-toggle-header {
    padding: 1.5rem 2rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.stats-toggle-header:hover {
    background: rgba(255,255,255,0.1);
}

.toggle-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.toggle-content h4 {
    color: white;
    margin: 0;
}

.toggle-badge {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.toggle-icon {
    color: white;
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.toggle-icon.rotated {
    transform: rotate(180deg);
}

.stats-content {
    padding: 2rem;
    display: none;
    animation: slideDown 0.3s ease;
}

.stats-content.show {
    display: block;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-overview {
    display: grid;
    grid-template-columns: repeat(3, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--card-color);
}

.stat-card.primary { --card-color: #007bff; }
.stat-card.success { --card-color: #28a745; }
.stat-card.info { --card-color: #17a2b8; }
.stat-card.warning { --card-color: #ffc107; }
.stat-card.danger { --card-color: #dc3545; }
.stat-card.dark { --card-color: #343a40; }

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--card-color);
    color: white;
    font-size: 1.5rem;
}

.stat-content {
    flex: 1;
}

.stat-content h3 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
}

.stat-content p {
    margin: 0.25rem 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    margin-top: 0.5rem;
}

.stat-trend.positive {
    color: #28a745;
}

.stat-trend.negative {
    color: #dc3545;
}

.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.chart-container {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.chart-header h5 {
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
}

.chart-content {
    height: 300px;
    position: relative;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.action-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin: 0 auto 1rem;
}

.action-content h6 {
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
    font-weight: 600;
}

.action-content p {
    margin: 0 0 1rem 0;
    color: #6c757d;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .modern-stats-dashboard {
        padding: 1rem;
    }
    
    .stats-overview {
        grid-template-columns: 1fr;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let orderStatusChart = null;
let revenueChart = null;
let customerChart = null;

document.addEventListener('DOMContentLoaded', function() { 
    
    // Initialize statistics as collapsed by default
    const statsContent = document.getElementById('modernStatsContent');
    const toggleIcon = document.getElementById('modernStatsToggleIcon');
    
    if (statsContent && toggleIcon) {
        statsContent.style.display = 'none';
        toggleIcon.classList.add('fa-chevron-down');
    }
});

function toggleModernStats() {
    const statsContent = document.getElementById('modernStatsContent');
    const toggleIcon = document.getElementById('modernStatsToggleIcon');
    
    if (statsContent && toggleIcon) {
        if (statsContent.style.display === 'none' || !statsContent.classList.contains('show')) {
            // Expand
            statsContent.style.display = 'block';
            statsContent.classList.add('show');
            toggleIcon.classList.remove('fa-chevron-down');
            toggleIcon.classList.add('fa-chevron-up');
            toggleIcon.classList.add('rotated');
        } else {
            // Collapse
            statsContent.style.display = 'none';
            statsContent.classList.remove('show');
            toggleIcon.classList.remove('fa-chevron-up');
            toggleIcon.classList.add('fa-chevron-down');
            toggleIcon.classList.remove('rotated');
        }
    }
} 

function showCustomerChart() {
    $('#customerChartModal').modal('show');
    
    $.ajax({
        url: '{{ route("admin.receipt-socials.customer-chart") }}',
        type: 'GET',
        success: function(data) {
            const ctx = document.getElementById('customerChart').getContext('2d');
            
            if (customerChart instanceof Chart) {
                customerChart.destroy();
            }
            
            customerChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels || ['عميل 1', 'عميل 2', 'عميل 3', 'عميل 4'],
                    datasets: [{
                        label: 'عدد العملاء',
                        data: data.values || [30, 25, 20, 15],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'توزيع العملاء حسب عدد الطلبات'
                        },
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }
    });
}
</script>
@endif 