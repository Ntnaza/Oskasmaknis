<template>
  <div>
    <main>
      <section class="relative py-32 bg-blueGray-800">
        <div class="container mx-auto px-4">
          <div class="flex flex-wrap justify-center">
            <div class="w-full lg:w-8/12 text-center">
              <h1 class="text-white font-semibold text-4xl">
                Kalender Kegiatan OSIS & MPK
              </h1>
              <p class="mt-4 text-lg leading-relaxed text-blueGray-300">
                Jangan lewatkan jadwal kegiatan sekolah! Semua informasi PORSENI, lomba, hingga jadwal upacara ada di sini.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section class="relative py-16 bg-blueGray-100">
        <div class="container mx-auto px-4">
          <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-xl rounded-lg bg-white p-4 sm:p-6">
            
            <FullCalendar :options="calendarOptions" />

          </div>
        </div>
      </section>
    </main>

    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto">
      <div class="fixed inset-0 bg-black opacity-50" @click="closeModal"></div>
      <div class="relative w-auto max-w-lg mx-auto my-6" style="min-width: 500px">
        <div class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg">
          <div class="flex items-start justify-between p-5 border-b border-solid rounded-t border-blueGray-200">
            <h3 class="text-2xl font-semibold">
              {{ selectedEvent.extendedProps.titleAsli || '(Tanpa Judul)' }}
            </h3>
            <button class="p-1 ml-auto bg-transparent border-0 text-black float-right text-3xl leading-none font-semibold" @click="closeModal">
              <span class="text-black h-6 w-6 text-2xl block">×</span>
            </button>
          </div>
          
          <div v-if="selectedEvent" class="relative p-6 flex-auto">
            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-white" :style="{ backgroundColor: selectedEvent.color }">
              {{ selectedEvent.extendedProps.category }}
            </span>
            <p class="text-blueGray-600 mt-4">
              <strong>Mulai:</strong> {{ formatModalDate(selectedEvent.extendedProps.startAsli) }}
            </p>
            <p v-if="selectedEvent.extendedProps.endAsli" class="text-blueGray-600">
              <strong>Selesai:</strong> {{ formatModalDate(selectedEvent.extendedProps.endAsli) }}
            </p>
            
            <hr class="my-4">
            
            <h4 class="font-semibold text-blueGray-700">Deskripsi:</h4>
            <p class="text-blueGray-600 whitespace-pre-wrap">
              {{ selectedEvent.extendedProps.description || '(Tidak ada deskripsi)' }}
            </p>
          </div>

          <div class="flex items-center justify-end p-6 border-t border-solid rounded-b border-blueGray-200">
            <button class="text-red-500 background-transparent font-bold uppercase px-6 py-2 text-sm" type="button" @click="closeModal">
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

export default {
  components: {
    FullCalendar
  },
  data() {
    return {
      showModal: false,
      selectedEvent: null,
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin, listPlugin],
        
        // Cek lebar layar: jika < 768px (HP/Tablet), pakai 'listMonth', 
        // jika tidak, pakai 'dayGridMonth'
        initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
        
        locale: 'id',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,listMonth'
        },
        buttonText: {
          today: 'Hari Ini',
          month: 'Bulan',
          list: 'Agenda Bulanan'
        },
        editable: false,
        selectable: false,
        weekends: true,
        expandRows: true, // Biarkan ini

        // Biarkan konten (CSS) yang menentukan tinggi kalender
        height: 'auto', 
        
        events: this.fetchCalendarEvents,
        eventClick: this.handleEventClick,
        
        displayEventTime: false, 
        eventContent: this.handleEventContent,
      }
    };
  },
  methods: {
    handleEventClick(clickInfo) {
      if (clickInfo.event.extendedProps && clickInfo.event.extendedProps.titleAsli) {
        this.selectedEvent = clickInfo.event;
        this.showModal = true;
      }
    },
    closeModal() {
      this.showModal = false;
      this.selectedEvent = null;
    },
    
    async fetchCalendarEvents(fetchInfo, successCallback, failureCallback) {
      try {
        const response = await axios.get('/api/calendar-activities', {
          params: {
            start: fetchInfo.startStr,
            end: fetchInfo.endStr,
          }
        });

        const processedEvents = response.data.flatMap(event => {
          const startTime = new Date(event.start);
          const hours = startTime.getHours().toString().padStart(2, '0');
          const minutes = startTime.getMinutes().toString().padStart(2, '0');
          const timeString = `${hours}.${minutes}`;
          const eventTitle = `${timeString} ${event.title}`;

          const extendedPropsData = {
              titleAsli: event.title,
              category: event.category,
              description: event.description,
              startAsli: event.start,
              endAsli: event.end
            };

          const backgroundEvent = {
            id: `${event.id}-bg`,
            groupId: event.id,
            start: event.start,
            end: event.end,
            allDay: true,
            display: 'background',
            color: event.color
          };

          const foregroundEvent = {
            id: event.id,
            groupId: event.id,
            title: eventTitle,
            start: event.start,
            end: event.end,
            allDay: true,
            color: 'transparent',
            textColor: '#1E293B',
            extendedProps: extendedPropsData
          };

          return [backgroundEvent, foregroundEvent];
        });

        successCallback(processedEvents);

      } catch (error) {
        console.error("Gagal mengambil data kalender:", error);
        failureCallback(error);
      }
    },
    
    formatModalDate(date) {
      if (!date) return '';
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(date).toLocaleString('id-ID', options);
    },

    /**
     * FUNGSI KONTEN EVENT (JUDUL TEBAL DI ATAS)
     */
    handleEventContent(arg) {
      const props = arg.event.extendedProps;
      const fullTitle = arg.event.title;
      
      // 1. Ambil Waktu
      const time = fullTitle.split(' ')[0]; // Hasil: "07.00"

      // 2. Siapkan variabel
      let title = props.titleAsli;
      let description = props.description;

      // 3. LOGIKA: Cek apakah judul 'titleAsli' valid
      if (!title || title.trim() === '' || title.includes('(Data Kegiatan Kosong)')) {
        // JIKA JUDUL TIDAK VALID: 'Curi' judul dari deskripsi.
        title = description; 
        description = ''; // Kosongkan deskripsi
      } 
      // JIKA JUDUL VALID: Biarkan saja.

      // 4. Potong deskripsi jika perlu
      const shortDesc = description ? (description.substring(0, 35) + (description.length > 35 ? '...' : '')) : '';

      // 5. Buat HTML
      const eventHtml = `
        <div class="fc-event-multi-line">
          <strong>${title || ''}</strong>
          <span>${time}</span>
          <span class="desc">${shortDesc}</span>
        </div>
      `;

      return { html: eventHtml };
    }
  }
};
</script>

<style>
/* Style kustomisasi FullCalendar */
.fc-button-primary {
  background-color: #1E293B !important;
  border-color: #1E293B !important;
  font-weight: 600 !important;
}
.fc-button-primary:hover {
  background-color: #334155 !important;
}
.fc-button-primary:disabled {
  background-color: #94A3B8 !important;
  border-color: #94A3B8 !important;
}
.fc .fc-daygrid-day.fc-day-today {
  background: #f0f9ff !important;
}

/* Sembunyikan format jam default (jaga-jaga) */
.fc-event-time {
  display: none !important;
}

/* --- STYLE UNTUK MULTI-LINE & TINGGI --- */

/* Ini adalah "pembungkus" konten di dalam setiap kotak hari */
/* Kita paksa tingginya minimal 110px */
.fc-daygrid-day-frame {
  min-height: 110px !important; /* Anda bisa sesuaikan angka 110px ini */
}

.fc-daygrid-event-harness {
  height: 100% !important;
  align-content: start !important; 
}

.fc-event[style*="background-color: transparent"] {
  background: none !important;
  border: none !important;
  white-space: normal !important; 
  overflow: visible !important; 
  height: 100% !important; 
}

.fc-event-multi-line {
  padding: 4px 5px;
  line-height: 1.4;
  color: #1E293B;
  height: 100%;
}

/* 4. Style untuk JUDUL (baris pertama) tebal */
.fc-event-multi-line strong {
  font-size: 0.9em;
  font-weight: 700;
  display: block;
}

/* 5. Style untuk WAKTU (baris kedua) */
.fc-event-multi-line span {
  font-size: 0.9em;
  display: block;
}

/* 6. Style untuk Deskripsi (baris ketiga) */
.fc-event-multi-line .desc {
  font-size: 0.8em;
  font-style: italic;
  opacity: 0.8;
  display: block;
}
</style>