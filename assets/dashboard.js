// Chart.js - Langsung pakai variabel PHP yang aman
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('statsChart');
    if (ctx && window.chartData) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Disetujui', 'Ditolak'],
                datasets: [{
                    data: [window.chartData.pending, window.chartData.approved, window.chartData.rejected],
                    backgroundColor: ['#F59E0B', '#10B981', '#EF4444'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
