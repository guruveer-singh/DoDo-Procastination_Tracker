@extends('layouts.app')

@section('content')
<div class="container">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-8">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Weekly Report</h2>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label for="week-select" class="text-sm text-gray-600 dark:text-gray-300">Week</label>
                    <select id="week-select" class="px-2 py-1 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                        <option value="this" selected>This week</option>
                        <option value="last">Last week</option>
                    </select>
                </div>
                <label class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-300">
                    <input type="checkbox" id="compare-last" class="rounded border-gray-300" /> Compare last week
                </label>
                <label class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-300">
                    <input type="checkbox" id="toggle-reminders" class="rounded border-gray-300" checked /> Reminders
                </label>
                <label class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-300">
                    <input type="checkbox" id="toggle-notes" class="rounded border-gray-300" checked /> Notes
                </label>
            </div>
        </div>
        <div class="relative" style="height: 260px;">
            <div id="chart-loading" class="absolute inset-0 flex items-center justify-center text-sm text-gray-500 dark:text-gray-400">Loading…</div>
            <canvas id="weeklyChart" class="hidden"></canvas>
        </div>
        <div id="chart-empty" class="hidden mt-2 text-xs text-gray-500 dark:text-gray-400">
            No activity found for the selected range.
        </div>
        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Tasks Created vs Completed. Optional: Reminders/Notes, and last week overlay.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function(){
    const brandYellow = '#f59e0b';
    const brandGray = getComputedStyle(document.documentElement).getPropertyValue('--text-secondary') || '#9ca3af';
    const brandTeal = '#14b8a6';
    const brandPurple = '#8b5cf6';

    const canvas = document.getElementById('weeklyChart');
    const loading = document.getElementById('chart-loading');
    const emptyEl = document.getElementById('chart-empty');
    if (!canvas) return;

    let chart;
    const labelsFmt = (isoDates) => isoDates.map(d => {
        const date = new Date(d);
        return date.toLocaleDateString(undefined, { weekday: 'short' });
    });

    async function fetchWeek(week = 'this') {
        const res = await fetch(`/analytics/weekly?week=${week}`);
        if (!res.ok) throw new Error('Failed to load analytics');
        return res.json();
    }

    function buildDatasets(primary, opts) {
        const ds = [];
        // Base series
        ds.push({
            label: 'Tasks Created',
            data: primary.series.tasks_created,
            tension: 0.35,
            borderColor: brandGray,
            backgroundColor: 'rgba(156,163,175,0.15)',
            fill: true,
            borderWidth: 2
        });
        ds.push({
            label: 'Tasks Completed',
            data: primary.series.tasks_completed,
            tension: 0.35,
            borderColor: brandYellow,
            backgroundColor: 'rgba(245,158,11,0.2)',
            fill: true,
            borderWidth: 2
        });

        if (opts.showReminders) {
            ds.push({
                label: 'Reminders',
                data: primary.series.reminders_created,
                tension: 0.35,
                borderColor: brandTeal,
                backgroundColor: 'rgba(20,184,166,0.15)',
                fill: true,
                borderWidth: 2
            });
        }
        if (opts.showNotes) {
            ds.push({
                label: 'Notes',
                data: primary.series.notes_created,
                tension: 0.35,
                borderColor: brandPurple,
                backgroundColor: 'rgba(139,92,246,0.15)',
                fill: true,
                borderWidth: 2
            });
        }

        if (opts.compare && opts.lastWeek) {
            ds.push({
                label: 'Tasks Completed (Last Week)',
                data: opts.lastWeek.series.tasks_completed,
                tension: 0.35,
                borderColor: brandYellow,
                borderDash: [6, 6],
                pointRadius: 0,
                fill: false,
                borderWidth: 2
            });
        }
        return ds;
    }

    async function render() {
        const week = document.getElementById('week-select').value;
        const showReminders = document.getElementById('toggle-reminders').checked;
        const showNotes = document.getElementById('toggle-notes').checked;
        const compare = document.getElementById('compare-last').checked && week === 'this';

        loading.classList.remove('hidden');
        canvas.classList.add('hidden');
        emptyEl.classList.add('hidden');

        try {
            const primary = await fetchWeek(week);
            const lastWeek = compare ? await fetchWeek('last') : null;

            const hasAny = Object.values(primary.series).some(arr => (arr || []).some(v => v > 0));
            if (!hasAny) {
                emptyEl.classList.remove('hidden');
            }

            const labels = labelsFmt(primary.labels);
            const datasets = buildDatasets(primary, { showReminders, showNotes, compare, lastWeek });

            const cfg = {
                type: 'line',
                data: { labels, datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'bottom', labels: { usePointStyle: true } },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            };

            if (chart) chart.destroy();
            chart = new Chart(canvas, cfg);
        } catch (e) {
            emptyEl.textContent = 'Unable to load report.';
            emptyEl.classList.remove('hidden');
        } finally {
            loading.classList.add('hidden');
            canvas.classList.remove('hidden');
        }
    }

    document.getElementById('week-select').addEventListener('change', render);
    document.getElementById('toggle-reminders').addEventListener('change', render);
    document.getElementById('toggle-notes').addEventListener('change', render);
    document.getElementById('compare-last').addEventListener('change', render);
    render();
})();
</script>
@endpush
