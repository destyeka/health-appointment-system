<x-app-layout>
    {{-- FullCalendar Library --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>

    <style>
        .fc {
            font-family: 'Inter', sans-serif;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 700;
            color: #111827;
        }

        .fc-button {
            border-radius: 8px !important;
            text-transform: capitalize;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 6px 16px !important;
        }

        .fc-button-primary {
            background-color: white !important;
            color: #374151 !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .fc-button-primary:hover {
            background-color: #f9fafb !important;
            color: #009688 !important;
            border-color: #009688 !important;
        }

        .fc-button-active {
            background-color: #e0f2f1 !important;
            border-color: #009688 !important;
            color: #00695c !important;
        }

        .fc-day-today {
            background-color: #f0fdfa !important;
        }

        .fc-event {
            border-radius: 6px;
            padding: 2px 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: all 0.2s;
            border-width: 0 0 0 3px;
            border-style: solid;
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-end mb-6 px-4 md:px-0">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Jadwal Praktek</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $doctor->name }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                {{-- FILTER BAR --}}
                <div
                    class="bg-gray-50 p-4 rounded-2xl border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Status
                            Appointment</label>
                        <select id="filterStatus" onchange="refreshCalendar()"
                            class="w-full rounded-xl border-gray-200 text-sm focus:ring-[#009688] focus:border-[#009688] py-2.5">
                            <option value="">Semua Status</option>
                            <option value="scheduled">Dijadwalkan</option>
                            <option value="on_going">Sedang Berlangsung</option>
                            <option value="finished">Selesai</option>
                        </select>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Tipe
                            Konsultasi</label>
                        <select id="filterType" onchange="refreshCalendar()"
                            class="w-full rounded-xl border-gray-200 text-sm focus:ring-[#009688] focus:border-[#009688] py-2.5">
                            <option value="">Semua Tipe</option>
                            <option value="offline">Offline</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                </div>

                {{-- CALENDAR --}}
                <div id='calendar'></div>
            </div>
        </div>
    </div>

    <script>
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            calendar = new FullCalendar.Calendar(calendarEl, {
                // CHANGE 1: Set default view to Day
                initialView: 'timeGridDay', 
                
                // CHANGE 2: Adjust header to prioritize Day/Week toggles
                headerToolbar: {
                    left: 'prev next today',
                    center: 'title',
                    right: 'dayGridMonth timeGridDay listWeek' // Removed Month view to focus on slots
                },

                // Calendar Settings
                slotMinTime: '07:00:00',     
                slotMaxTime: '21:00:00',     
                allDaySlot: false,           // Remove the "All Day" row at top
                slotDuration: '00:15:00',    // Finer granularity (15 mins) for better visuals
                slotLabelInterval: '01:00',  // Label every hour
                nowIndicator: true,
                expandRows: true,            // Stretch to fill height
                height: 'auto',              // Auto height
                
                events: {
                    url: '{{ route("appointments.doctor.api") }}',
                    extraParams: function() {
                        return {
                            status: document.getElementById('filterStatus').value,
                            consultation_type: document.getElementById('filterType').value
                        };
                    }
                },
                eventContent: function(arg) {
                    // ... (Keep your existing custom render logic here) ...
                    let queue = arg.event.extendedProps.queue;
                    let title = arg.event.title;
                    let content = document.createElement('div');
                    
                    if(arg.event.extendedProps.status === 'on_going') {
                         content.innerHTML = `
                            <div class="flex items-center gap-1 leading-tight text-xs font-semibold">
                                <span>${title}</span>
                            </div>
                            <div class="text-[10px] opacity-75 mt-0.5 animate-pulse">● Active Now</div>
                        `;
                    } else {
                        content.innerHTML = `<div class="text-xs font-semibold">${title}</div>`;
                    }
                    return { domNodes: [content] }
                },
                eventClick: function(info) {
                    // Redirect to manage page
                    window.location.href = `/appointments/${info.event.id}/manage`;
                }
            });
            calendar.render();
        });

        function refreshCalendar() {
            calendar.refetchEvents();
        }

        function handleAction(id, action) {
            let confirmMsg = 'Lanjutkan aksi ini?';
            if (action === 'start') confirmMsg = 'Mulai sesi konsultasi?';
            if (action === 'end') confirmMsg = 'Selesaikan sesi?';
            if (action === 'skip') confirmMsg = 'Skip pasien ini?';

            if (!confirm(confirmMsg)) return;

            const url = `/appointments/${id}/${action}`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeModal();
                        calendar.refetchEvents();
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(err => console.error(err));
        }
    </script>
</x-app-layout>