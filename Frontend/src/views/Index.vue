<template>
  <div>
    <index-navbar />
    
    <section class="header relative pt-16 items-center flex flex-col sm:flex-row h-auto sm:h-screen max-h-860-px overflow-hidden"> 
      <div class="container mx-auto items-center flex flex-wrap">
        <div class="w-full md:w-8/12 lg:w-6/12 xl:w-6/12 px-4 z-10">
          <div class="pt-32 sm:pt-0">
            <h2 class="font-semibold text-4xl text-blueGray-600">
              {{ pageContentStore.content.index?.title || "Memuat..." }}
            </h2>
            <p class="mt-4 text-lg leading-relaxed text-blueGray-500">
              {{ pageContentStore.content.index?.subtitle || "Harap tunggu sebentar..." }}
            </p>
            <div class="mt-12">
              <router-link to="/landing" class="get-started text-white font-bold px-6 py-4 rounded outline-none focus:outline-none mr-1 mb-1 bg-emerald-500 active:bg-emerald-600 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150">
                Jelajahi disini
              </router-link>
              <router-link to="/berita-dan-galeri" class="github-star ml-1 text-white font-bold px-6 py-4 rounded outline-none focus:outline-none mr-1 mb-1 bg-blueGray-700 active:bg-blueGray-600 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150">
                Berita Terbaru
              </router-link>
            </div>
          </div>
        </div>
      </div>
      <div class="relative sm:absolute sm:top-0 sm:right-0 w-full sm:w-6/12 h-auto sm:h-full flex items-center justify-center mt-12 sm:mt-0 pt-0 sm:pt-16 sm:-mt-20">
        <div class="relative w-72 h-72"> 
          <img
            v-for="(logo, index) in logos"
            :key="logo.alt"
            :src="logo.src"
            :alt="logo.alt"
            class="absolute inset-0 w-full h-full object-contain transition-all duration-1000 ease-in-out"
            :class="{
              'opacity-100 scale-100 z-10': index === activeLogoIndex,
              'opacity-40 scale-85 z-0 transform translate-x-20 translate-y-10': index !== activeLogoIndex 
            }"
            style="transition-delay: 50ms;" 
          />
        </div>
      </div>
    </section>

    <section class="mt-48 md:mt-40 pb-40 relative bg-blueGray-50">
      <div class="-mt-20 top-0 bottom-auto left-0 right-0 w-full absolute h-20" style="transform: translateZ(0);">
        <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
          <polygon class="text-blueGray-50 fill-current" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
      <div class="container mx-auto">
        <div class="flex flex-wrap items-center">
          <div class="w-10/12 md:w-6/12 lg:w-4/12 px-12 md:px-4 mr-auto ml-auto -mt-32">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg bg-emerald-500">
              <img
                alt="Foto Ketua OSIS"
                :src="contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_osis_image_path
                  ? `${backendUrl}/storage/${contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_osis_image_path}`
                  : 'https://placehold.co/700x800?text=Foto+Ketua+OSIS'"
                class="w-full align-middle rounded-t-lg h-auto object-cover"
              />
              <blockquote class="relative p-8 mb-4">
                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95" class="absolute left-0 w-full block h-95-px -top-94-px">
                  <polygon points="-30,95 583,95 583,65" class="text-emerald-500 fill-current"></polygon>
                </svg>
                <h4 class="text-xl font-bold text-white">{{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_osis_name || '...' }}</h4>
                <p class="text-md font-light mt-2 text-white">"{{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_osis_sambutan || '...' }}"</p>
              </blockquote>
            </div>
          </div>
          <div class="w-full md:w-6/12 px-4">
            <h4 class="text-2xl font-semibold mb-4 text-blueGray-700">Program Kerja Unggulan OSIS</h4>
            <div class="flex flex-wrap">
              <div v-if="featuredPrograms.length > 0" class="w-full flex flex-wrap">
                <div v-for="program in featuredPrograms" :key="program.id" class="w-full lg:w-6/12 px-4">
                  <div class="relative flex flex-col mt-4">
                    <div class="px-4 py-5 flex-auto">
                      <div class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-white">
                        <i class="fas fa-tasks"></i>
                      </div>
                      <h6 class="text-xl mb-1 font-semibold">{{ program.title }}</h6>
                      <p class="mb-4 text-blueGray-500">{{ program.description }}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="w-full px-4">
                <p class="text-lg font-light leading-relaxed mt-4 mb-4 text-blueGray-600">
                  Belum ada program kerja unggulan OSIS yang ditampilkan.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 relative bg-blueGray-100">
      <div class="container mx-auto">
        <div class="flex flex-wrap items-center">
           <div class="w-full md:w-6/12 px-4">
             <div class="relative flex flex-col mt-4">
               <div class="px-4 py-5 flex-auto">
                 <div class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-white">
                   <i class="fas fa-eye"></i>
                 </div>
                 <h6 class="text-xl mb-1 font-semibold">Visi MPK</h6>
                 <p class="mb-4 text-blueGray-500">
                   {{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_visi || 'Visi MPK belum diatur.' }}
                 </p>
               </div>
             </div>
             <div class="relative flex flex-col mt-4">
               <div class="px-4 py-5 flex-auto">
                 <div class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-white">
                   <i class="fas fa-bullseye"></i>
                 </div>
                 <h6 class="text-xl mb-1 font-semibold">Misi MPK</h6>
                 <p class="mb-4 text-blueGray-500">
                   {{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_misi || 'Misi MPK belum diatur.' }}
                 </p>
               </div>
             </div>
           </div>
           <div class="w-10/12 md:w-6/12 lg:w-4/12 px-12 md:px-4 mr-auto ml-auto">
             <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg bg-lightBlue-500">
              <img
                alt="Foto Ketua MPK"
                :src="contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_image_path
                  ? `${backendUrl}/storage/${contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_image_path}`
                  : 'https://placehold.co/700x800?text=Foto+Ketua+MPK'"
                class="w-full align-middle rounded-t-lg h-auto object-cover"
              />
              <blockquote class="relative p-8 mb-4">
                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95" class="absolute left-0 w-full block h-95-px -top-94-px">
                  <polygon points="-30,95 583,95 583,65" class="text-lightBlue-500 fill-current"></polygon>
                </svg>
                <h4 class="text-xl font-bold text-white">{{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_name || '...' }}</h4>
                <p class="text-md font-light mt-2 text-white">"{{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_sambutan || '...' }}"</p>
              </blockquote>
             </div>
           </div>
         </div>
      </div>
    </section>
    
    <section class="pb-20 pt-20 relative block bg-blueGray-50">
      <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center text-center mb-12">
          <div class="w-full lg:w-6/12 px-4">
            <h2 class="text-4xl font-semibold text-blueGray-700">
              {{ contentBlockStore.blocks['index-pembina']?.content.title || 'Dibimbing Oleh' }}
            </h2>
          </div>
        </div>
        <div class="flex flex-wrap items-center justify-center">
          <div class="w-full md:w-4/12 lg:w-3/12 px-4 mb-8 md:mb-0">
            <img
              alt="Foto Pembina"
              :src="contentBlockStore.blocks['index-pembina']?.content.pembina_image_path
                ? `${backendUrl}/storage/${contentBlockStore.blocks['index-pembina'].content.pembina_image_path}`
                : 'https://demos.creative-tim.com/vue-notus/img/team-1-800x800.jpg'"
              class="mx-auto w-48 h-48 md:w-52 md:h-52 object-contain"
            />
          </div>
          <div class="w-full md:w-5/12 lg:w-4/12 px-4 text-center md:text-left">
            <h5 class="text-2xl font-bold text-blueGray-700">
              {{ contentBlockStore.blocks['index-pembina']?.content.pembina_name || 'Nama Pembina' }}
            </h5>
            <p class="mt-1 text-lg text-blueGray-500 uppercase font-semibold">
              {{ contentBlockStore.blocks['index-pembina']?.content.pembina_title || 'Jabatan Pembina' }}
            </p>
          </div>
        </div>
      </div>
    </section> 

    <section class="block relative z-1 bg-blueGray-600">
      <div class="container mx-auto px-4 py-24">
        <div class="justify-center flex flex-wrap">
          <div class="w-full lg:w-12/12 px-4">
            <div class="flex flex-wrap">
              <div class="w-full text-center lg:w-8/12 mx-auto mb-12">
                <h3 class="font-semibold text-3xl text-white">Berita & Galeri Terbaru</h3>
                <p class="text-blueGray-300 text-lg leading-relaxed mt-4">
                  Ikuti kegiatan dan informasi terbaru dari OSIS & MPK melalui berita dan galeri kegiatan kami.
                </p>
              </div>
              <div v-if="articleStore.latestArticles && articleStore.latestArticles.length > 0" class="w-full flex flex-wrap justify-center">
                <div v-for="article in articleStore.latestArticles" :key="article.id" class="w-full lg:w-4/12 px-4">
                  <router-link :to="'/berita/' + article.slug">
                    <div class="hover:-mt-4 relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg ease-linear transition-all duration-150">
                      <img
                        alt="Gambar Berita"
                        class="align-middle border-none max-w-full h-auto rounded-t-lg object-cover" style="height: 250px"
                        :src="article.image_url || 'https://placehold.co/600x400'"
                      />
                      <div class="px-4 py-5 flex-auto">
                        <h5 class="text-xl font-semibold pb-4 truncate">
                          {{ article.title }}
                        </h5>
                        <p class="text-blueGray-500 mb-4 h-24 overflow-hidden">
                          {{ article.excerpt }}
                        </p>
                        <span class="text-xs font-semibold text-blueGray-400">
                          {{ new Date(article.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                        </span>
                      </div>
                    </div>
                  </router-link>
                </div>
              </div>
              <div v-else class="w-full text-center text-blueGray-300">
                <p>Memuat berita terbaru...</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 bg-white">
      <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center">
          <div class="w-full md:w-6/12 px-4 ml-auto mr-auto mt-32">
            <div class="justify-center flex flex-wrap relative">
              <div class="my-4 w-full lg:w-6/12 px-4">
                <template v-if="contentBlockStore.blocks['index-kolaborasi-jurusan']?.content.jurusan_list">
                  <a href="javascript:void(0);" v-for="(jurusan) in contentBlockStore.blocks['index-kolaborasi-jurusan'].content.jurusan_list.slice(0, 3)" :key="jurusan.name">
                    <div :class="[jurusan.color, 'shadow-lg rounded-lg text-center p-8 mt-8']">
                      <img alt="Logo Jurusan" class="max-w-full w-24 h-24 object-contain mx-auto" :src="`/assets/img/jurusan/${jurusan.logo_filename}`"/>
                      <p class="text-lg text-white mt-4 font-semibold">{{ jurusan.name }}</p>
                    </div>
                  </a>
                </template>
              </div>
              <div class="my-4 w-full lg:w-6/12 px-4 lg:mt-16">
                 <template v-if="contentBlockStore.blocks['index-kolaborasi-jurusan']?.content.jurusan_list">
                   <a href="javascript:void(0);" v-for="(jurusan) in contentBlockStore.blocks['index-kolaborasi-jurusan'].content.jurusan_list.slice(3, 6)" :key="jurusan.name">
                     <div :class="[jurusan.color, 'shadow-lg rounded-lg text-center p-8 mt-8']">
                       <img alt="Logo Jurusan" class="max-w-full w-24 h-24 object-contain mx-auto" :src="`/assets/img/jurusan/${jurusan.logo_filename}`"/>
                       <p class="text-lg text-white mt-4 font-semibold">{{ jurusan.name }}</p>
                     </div>
                   </a>
                 </template>
              </div>
            </div>
          </div>
          <div class="w-full md:w-4/12 px-12 md:px-4 ml-auto mr-auto mt-48">
            <div class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-white">
              <i class="fas fa-drafting-compass text-xl"></i>
            </div>
            <h3 class="text-3xl mb-2 font-semibold leading-normal">
              {{ contentBlockStore.blocks['index-kolaborasi-jurusan']?.content.title || '...' }}
            </h3>
            <p class="text-lg font-light leading-relaxed mt-4 mb-4 text-blueGray-600">
              {{ contentBlockStore.blocks['index-kolaborasi-jurusan']?.content.description || '...' }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 bg-white">
  <div class="container mx-auto px-4">
    
    <div class="flex flex-wrap justify-center text-center mb-12">
      <div class="w-full lg:w-8/12 px-4">
        <h2 class="text-4xl font-semibold text-blueGray-700">
          Galeri Aspirasi Publik
          <span v-if="!publicAspirationsLoading" class="text-2xl text-blueGray-500">
            (Total: {{ publicAspirations.total }})
          </span>
        </h2>
        <p class="text-lg leading-relaxed mt-4 mb-4 text-blueGray-500">
          Lihat aspirasi yang telah kami terima dan sedang ditindaklanjuti.
        </p>
      </div>
    </div>

    <div class="flex flex-wrap justify-between items-center mb-6 px-4">
        <div class="w-full md:w-1/2 lg:w-1/3 mb-4 md:mb-0">
          <div class="relative flex w-full flex-wrap items-stretch">
            <span class="z-10 h-full leading-snug font-normal text-center text-blueGray-300 absolute bg-transparent rounded text-base items-center justify-center w-8 pl-3 py-3">
              <i class="fas fa-search"></i>
            </span>
            <input type="text" v-model="publicAspirationSearch" @keydown.enter.prevent="applyPublicAspirationFilters" placeholder="Cari berdasarkan judul..." class="border-gray-300 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full pl-10"/>
          </div>
        </div>
        <div class="w-full md:w-1/2 lg:w-1/4">
          <select v-model="publicAspirationCategory" @change="applyPublicAspirationFilters" class="border-gray-300 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full">
            <option value="">Semua Kategori</option>
            <option value="Fasilitas">Fasilitas</option>
            <option value="Akademik">Akademik</option>
            <option value="Event">Event / Kegiatan</option>
            <option value="Kesiswaan">Kesiswaan</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>
    </div>

    <div v-if="publicAspirationsLoading" class="text-center py-10">
      <i class="fas fa-spinner fa-spin text-3xl text-blueGray-500"></i>
      <p class="mt-2 text-blueGray-500">Memuat aspirasi publik...</p>
    </div>

    <div v-if="!publicAspirationsLoading && (!publicAspirations.data || publicAspirations.data.length === 0)" class="text-center py-10 px-6 bg-blueGray-50 rounded-lg">
      <i class="fas fa-inbox text-4xl text-blueGray-300"></i>
      <h4 class="text-xl font-semibold text-blueGray-600 mt-4">Belum Ada Aspirasi Publik</h4>
      <p class="text-blueGray-500 mt-2">Tidak ada aspirasi yang cocok dengan filter Anda atau belum ada aspirasi yang disetujui untuk tampil.</p>
    </div>

    <div v-if="!publicAspirationsLoading && publicAspirations.data && publicAspirations.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="item in publicAspirations.data" :key="item.id" class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-lg rounded-lg border">
        <div class="px-6 py-5 flex-auto">
          <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-emerald-600 bg-emerald-200 last:mr-0 mr-1">
            {{ item.category }}
          </span>

          <div class="mt-2" v-if="item.rating">
            <span v-for="star in 5" :key="star" class="text-sm" :class="star <= item.rating ? 'text-yellow-400' : 'text-blueGray-300'">
              <i class="fas fa-star"></i>
            </span>
          </div>
          
          <h6 class="text-xl font-semibold mt-3 mb-1 text-blueGray-700 truncate" :title="item.subject">{{ item.subject }}</h6>
          <p class="mb-4 text-blueGray-500 text-sm h-20 overflow-hidden text-ellipsis">
            {{ item.message }}
          </p>
          <hr class="my-3 border-blueGray-200">
          <div class="flex justify-between items-center">
            <span class="px-2 py-1 rounded-full text-xs font-bold text-white" :class="statusColor(item.status)">
              {{ item.status }}
            </span>
            <span class="text-xs text-blueGray-400 font-semibold">
              {{ formatRelativeTime(item.created_at) }}
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <div v-if="!publicAspirationsLoading && publicAspirations.data && publicAspirations.data.length > 0" class="flex justify-center mt-8">
      <button 
        @click="fetchPublicAspirations(publicAspirations.current_page - 1)" 
        :disabled="!publicAspirations.prev_page_url" 
        class="bg-blueGray-700 text-white font-bold px-6 py-3 rounded outline-none focus:outline-none mr-1 mb-1 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
      </button>
      <button 
        @click="fetchPublicAspirations(publicAspirations.current_page + 1)" 
        :disabled="!publicAspirations.next_page_url" 
        class="bg-blueGray-700 text-white font-bold px-6 py-3 rounded outline-none focus:outline-none ml-1 mb-1 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
      </button>
    </div>
    
  </div>
</section>

<section class="py-20 relative bg-blueGray-100">
  <div class="container mx-auto px-4">
    <div class="flex flex-wrap justify-center text-center mb-12">
      <div class="w-full lg:w-8/12 px-4">
        <h2 class="text-4xl font-semibold text-blueGray-700">Sampaikan Aspirasi Anda</h2>
        <p class="text-lg leading-relaxed mt-4 mb-4 text-blueGray-500">
          Punya ide, kritik, atau saran untuk kemajuan sekolah? Sampaikan langsung kepada kami secara anonim atau cantumkan nama Anda.
        </p>
      </div>
    </div>

    <div class="flex flex-wrap justify-center">
      <div class="w-full lg:w-10/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-white">
          <div class="flex-auto p-5 lg:p-10 relative overflow-hidden">
            
            <form @submit.prevent="submitAspiration" v-if="!successMessage">
              <div class="flex flex-wrap">
                  <div class="w-full lg:w-6/12 pr-0 lg:pr-4">
                    <div class="relative w-full mb-3">
                      <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="category">
                        Kategori <span class="text-red-500">*</span>
                      </label>
                      <select v-model="form.category" id="category" class="border-gray-300 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" required>
                        <option value="" disabled>-- Pilih Kategori --</option>
                        <option value="Fasilitas">Fasilitas</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Event">Event / Kegiatan</option>
                        <option value="Kesiswaan">Kesiswaan</option>
                        <option value="Lainnya">Lainnya</option>
                      </select>
                    </div>
                  </div>
                  <div class="w-full lg:w-6/12 lg:pl-4">
                    <div class="relative w-full mb-3">
                      <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="subject">
                        Judul / Perihal <span class="text-red-500">*</span>
                      </label>
                      <input type="text" v-model="form.subject" id="subject" class="border-gray-300 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Cth: Toilet lantai 2..." required />
                    </div>
                  </div>
              </div>

              <div class="relative w-full mb-3">
                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="message">
                  Isi Aspirasi <span class="text-red-500">*</span>
                </label>
                <textarea v-model="form.message" id="message" class="border-gray-300 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" rows="4" placeholder="Jelaskan aspirasi Anda secara detail..." required></textarea>
              </div>

              <div class="relative w-full mb-3">
                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">
                  Rating Kepuasan <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-1">
                  <span 
                    v-for="star in 5" 
                    :key="star"
                    @click="form.rating = star"
                    @mouseover="hoverRating = star"
                    @mouseleave="hoverRating = 0"
                    class="star-rating"
                    :class="{'star-filled': (hoverRating || form.rating) >= star}"
                  >
                    <i class="fas fa-star"></i>
                  </span>
                </div>
                <small v-if="formErrors.rating" class="text-red-500 mt-1 text-xs">{{ formErrors.rating }}</small>
              </div>
              
              <div class="relative w-full mb-3">
                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="attachment">
                  Lampiran (Opsional)
                </label>
                <input type="file" @change="handleFileUpload" id="attachment" class="border-gray-300 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" />
                <small class="text-blueGray-500">Maks 5MB. Tipe: jpg, png, pdf, docx.</small>
              </div>

              <hr class="mt-6 border-b-1 border-blueGray-300" />

              <div class="relative w-full mb-3 mt-3">
                   <input type="checkbox" v-model="isAnonim" id="anonim" class="form-checkbox border-gray-300 rounded text-blueGray-700 ml-1 w-5 h-5 ease-linear transition-all duration-150" />
                <label class="inline-flex items-center cursor-pointer ml-2 text-blueGray-600 font-bold" htmlFor="anonim">
                  Kirim sebagai Anonim (Tanpa Nama)
                </label>
              </div>
              <div v-if="!isAnonim" class="flex flex-wrap">
                <div class="w-full lg:w-6/12 pr-0 lg:pr-4">
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="name">
                      Nama Anda <span class="text-red-500">*</span>
                    </label>
                    <input type="text" v-model="form.name" id="name" class="border-gray-300 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Nama Lengkap" :required="!isAnonim" />
                  </div>
                </div>
                <div class="w-full lg:w-6/12 lg:pl-4">
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="contact">
                      Kontak (Email/WA - Opsional)
                    </label>
                    <input type="text" v-model="form.contact" id="contact" class="border-gray-300 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Kontak agar kami bisa hubungi" />
                  </div>
                </div>
              </div>

              <div class="text-center mt-6">
                <button class="bg-blueGray-800 text-white active:bg-blueGray-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150" type="submit" :disabled="isLoading">
                  <i v-if="isLoading" class="fas fa-spinner fa-spin mr-2"></i>
                  {{ isLoading ? 'Mengirim...' : 'Kirim Aspirasi' }}
                </button>
              </div>
            </form>
            
            <div v-if="successMessage" class="text-center py-10 px-6">
              <canvas id="confetti-canvas" class="absolute top-0 left-0 w-full h-full z-0"></canvas>
              
              <div class="relative z-10">
                <div class="text-green-500 mb-4">
                  <i class="fas fa-check-circle fa-5x"></i>
                </div>
                <h3 class="text-3xl font-semibold text-blueGray-700 mb-2">
                  Aspirasi Terkirim!
                </h3>
                <p class="text-lg text-blueGray-500 mb-6">
                  Terima kasih telah menyampaikan aspirasi Anda. Kami akan segera meninjaunya.
                </p>
                <button @click="resetForm" class="bg-blueGray-700 text-white font-bold px-6 py-3 rounded outline-none focus:outline-none mr-1 mb-1 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150">
                  Kirim Aspirasi Lain
                </button>
              </div>
            </div>

            <div v-if="errorMessage" class="text-center p-6 rounded-lg bg-red-100 text-red-800">
               <i class="fas fa-exclamation-triangle fa-2x text-red-600 mb-3"></i>
               <h4 class="text-xl font-semibold text-red-800 mb-2">Oops! Terjadi Kesalahan</h4>
               <p>{{ errorMessage }}</p>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    <section class="py-20 bg-blueGray-600 overflow-hidden">
      <div class="container mx-auto pb-64">
        <div class="flex flex-wrap justify-center">
          <div class="w-full md:w-5/12 px-12 md:px-4 ml-auto mr-auto md:mt-64">
            <div
              class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-white"
            >
              <i class="fas fa-landmark text-xl"></i>
            </div>
            <h3 class="text-3xl mb-2 font-semibold leading-normal text-white">
              {{ contentBlockStore.blocks['index-sejarah-oska']?.content.title || 'Sejarah Singkat OSKA' }}
            </h3>
            <p
              class="text-lg font-light leading-relaxed mt-4 mb-4 text-blueGray-400"
            >
              {{ contentBlockStore.blocks['index-sejarah-oska']?.content.description || 'Memuat sejarah...' }}
            </p>
            <router-link
              to="/profile"
              class="mt-4 inline-block text-white font-bold px-6 py-4 rounded outline-none focus:outline-none mr-1 mb-1 bg-blueGray-700 active:bg-blueGray-600 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150"
            >
              Baca Lebih Lanjut
            </router-link>
          </div>

          <div class="w-full md:w-4/12 px-4 mr-auto ml-auto mt-32 relative">
            <img
              :src="'/assets/img/logo-oska.png'" alt="Logo OSKA"
              class="absolute top-0 right-0 left-0 w-full h-auto max-w-full opacity-50"
              style="z-index: 0; transform: translateY(-50px);" />
          </div>
        </div>
      </div>
    </section>

    <section class="pb-16 bg-blueGray-200 relative pt-32">
      <div
        class="-mt-20 top-0 bottom-auto left-0 right-0 w-full absolute h-20"
        style="transform: translateZ(0);"
      >
        <svg
          class="absolute bottom-0 overflow-hidden"
          xmlns="http://www.w3.org/2000/svg"
          preserveAspectRatio="none"
          version="1.1"
          viewBox="0 0 2560 100"
          x="0"
          y="0"
        >
          <polygon
            class="text-blueGray-200 fill-current"
            points="2560 0 2560 100 0 100"
          ></polygon>
        </svg>
      </div>
      <div class="container mx-auto">
        <div
          class="flex flex-wrap justify-center bg-white shadow-xl rounded-lg -mt-64 py-16 px-12 relative z-10"
        >
          <div class="w-full text-center lg:w-8/1G2">
            <p class="text-4xl text-center">
              <span role="img" aria-label="love">
                😍
              </span>
            </p>
            <h3 class="font-semibold text-3xl">
              {{ contentBlockStore.blocks['index-ajakan-bergabung']?.content.title || '...' }}
            </h3>
            <p class="text-blueGray-500 text-lg leading-relaxed mt-4 mb-4">
              {{ contentBlockStore.blocks['index-ajakan-bergabung']?.content.description || '...' }}
            </p>
            <div class="sm:block flex flex-col mt-10">
              <router-link
                to="/landing"
                class="get-started text-white font-bold px-4 py-3 rounded outline-none focus:outline-none mr-1 mb-2 bg-blueGray-700 active:bg-blueGray-600 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150"
              >
                Cari Tahu Lebih Lanjut
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer-component />
  </div>
</template>
<style scoped>
.star-rating {
  font-size: 2rem; /* Ukuran bintang */
  color: #cbd5e1; /* Warna bintang kosong (Abu-abu) */
  cursor: pointer;
  transition: color 0.2s ease-in-out, transform 0.1s ease-in-out;
}
.star-rating:hover {
  transform: scale(1.1);
}
.star-filled {
  color: #FBBF24; /* Warna bintang terisi (Kuning) */
}
</style>
<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { usePageContentStore } from '@/stores/pageContent.js';
import { useContentBlockStore } from '@/stores/contentBlock.js';
import { useWorkProgramStore } from '@/stores/workProgram.js';
import { useArticleStore } from '@/stores/article.js';

// eslint-disable-next-line no-unused-vars
import IndexNavbar from "@/components/Navbars/IndexNavbar.vue";
// eslint-disable-next-line no-unused-vars
import FooterComponent from "@/components/Footers/Footer.vue";

// --- IMPORT BARU UNTUK FORM ---
import axios from 'axios';
import confetti from 'canvas-confetti'; // <-- [UPDATE 3] Import Confetti

// eslint-disable-next-line no-unused-vars
const backendUrl = 'http://localhost:8000';

const pageContentStore = usePageContentStore();
const contentBlockStore = useContentBlockStore();
const workProgramStore = useWorkProgramStore();
const articleStore = useArticleStore();

const logos = ref([
  { src: '/assets/img/logo-oska.png', alt: 'Logo OSKA' },
  { src: '/assets/img/logo-sekolah.png', alt: 'Logo Sekolah' },
]);

const activeLogoIndex = ref(0);
let intervalId = null;

// --- LOGIKA UNTUK KOTAK ASPIRASI (FORMULIR) ---
const isAnonim = ref(false);
const hoverRating = ref(0); // <-- Logika untuk hover bintang
const form = ref({
  subject: '',
  category: '',
  message: '',
  rating: 0, // <-- Nilai rating (1-5)
  name: '',
  contact: '',
  attachment: null,
});
const formErrors = ref({}); // <-- [UPDATE 1] Ref untuk menampung error validasi
const isLoading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const ticketId = ref('');

// eslint-disable-next-line no-unused-vars
function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file && file.size > 5 * 1024 * 1024) { // 5MB limit
    alert("Ukuran file terlalu besar! Maksimal 5MB.");
    event.target.value = null; // Reset input file
    return;
  }
  form.value.attachment = file;
}

/**
 * [UPDATE 3] Fungsi untuk memicu confetti
 * Mengambil canvas dari template dan menembakkan confetti.
 */
// eslint-disable-next-line no-unused-vars
function triggerConfetti() {
  const canvas = document.getElementById('confetti-canvas');
  if (canvas) {
    const myConfetti = confetti.create(canvas, {
      resize: true,
      useWorker: true
    });
    myConfetti({
      particleCount: 150, // Jumlah partikel
      spread: 100,         // Seberapa lebar sebarannya
      origin: { y: 0.6 }  // Mulai dari tengah layar (agak ke atas)
    });
  }
}

// eslint-disable-next-line no-unused-vars
async function submitAspiration() {
  // [UPDATE 1] Bersihkan error lama dan lakukan validasi di frontend
  formErrors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  // [UPDATE 1] Validasi Rating Wajib
  if (form.value.rating === 0) {
    formErrors.value.rating = 'Rating kepuasan wajib diisi.';
    return; // Hentikan fungsi jika validasi gagal
  }

  // Jika validasi lolos, lanjutkan
  isLoading.value = true;

  const formData = new FormData();
  formData.append('subject', form.value.subject);
  formData.append('category', form.value.category);
  formData.append('message', form.value.message);

  // [UPDATE 1] Rating sekarang wajib, jadi bisa langsung di-append
  formData.append('rating', form.value.rating);

  if (!isAnonim.value) {
    formData.append('name', form.value.name);
    formData.append('contact', form.value.contact);
  }

  if (form.value.attachment) {
    formData.append('attachment', form.value.attachment);
  }

  try {
    const response = await axios.post('/api/aspirations', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    successMessage.value = response.data.message;
    ticketId.value = response.data.ticket_id;

    // [UPDATE 3] Panggil confetti di sini setelah sukses!
    triggerConfetti();

    // Refresh galeri publik agar data baru (jika statusnya 'Sudah Dibaca') bisa muncul
    fetchPublicAspirations(1);

  } catch (error) {
    if (error.response && error.response.status === 422) {
      // Jika backend (Laravel) mengirim error validasi per-field
      if (error.response.data.errors) {
        // Ubah error backend menjadi format yang bisa dibaca `formErrors`
        formErrors.value = Object.fromEntries(
          Object.entries(error.response.data.errors).map(([key, value]) => [key, value[0]])
        );
        errorMessage.value = "Gagal mengirim: Periksa kembali isian Anda.";
      } else {
        errorMessage.value = "Gagal mengirim: Pastikan semua kolom yang wajib diisi sudah benar.";
      }
    } else {
      errorMessage.value = "Terjadi kesalahan di server. Silakan coba lagi nanti.";
    }
    console.error("Gagal mengirim aspirasi:", error);
  } finally {
    isLoading.value = false;
  }
}

// eslint-disable-next-line no-unused-vars
function resetForm() {
  isAnonim.value = false;
  form.value = {
    subject: '', category: '', message: '',
    rating: 0, // <-- Reset rating
    name: '', contact: '', attachment: null,
  };
  hoverRating.value = 0; // <-- Reset hover
  successMessage.value = '';
  errorMessage.value = '';
  formErrors.value = {}; // <-- [UPDATE 1] Bersihkan error validasi juga
  ticketId.value = '';
  const inputFile = document.getElementById('attachment');
  if (inputFile) inputFile.value = '';
}
// --- BATAS LOGIKA FORMULIR ---


// --- LOGIKA BARU UNTUK GALERI ASPIRASI PUBLIK ---
const publicAspirations = ref({ data: [], total: 0 }); // Inisialisasi sebagai objek dengan data dan total
const publicAspirationsLoading = ref(true);
const publicAspirationSearch = ref('');
const publicAspirationCategory = ref('');

/**
 * Mengambil data aspirasi publik dari API
 * @param {number} page - Nomor halaman yang ingin diambil
 */
// eslint-disable-next-line no-unused-vars
async function fetchPublicAspirations(page = 1) {
  publicAspirationsLoading.value = true;
  try {
    // Siapkan parameter (termasuk .value untuk Vue 3 <script setup>)
    const params = {
      page: page,
      search: publicAspirationSearch.value,
      category: publicAspirationCategory.value,
    };

    const response = await axios.get('/api/aspirations/public', { params });
    publicAspirations.value = response.data;
  } catch (error) {
    console.error("Gagal mengambil aspirasi publik:", error);
    // Tampilkan pesan error di galeri jika perlu
  } finally {
    publicAspirationsLoading.value = false;
  }
}

/**
 * Menerapkan filter dan search, lalu kembali ke halaman 1
 */
// eslint-disable-next-line no-unused-vars
function applyPublicAspirationFilters() {
  // Selalu reset ke halaman 1 saat filter atau search
  fetchPublicAspirations(1);
}

/**
 * Helper untuk warna status
 */
// eslint-disable-next-line no-unused-vars
function statusColor(status) {
  switch (status) {
    case 'Sudah Dibaca': return 'bg-sky-500';
    case 'Sedang Didiskusikan': return 'bg-orange-500';
    case 'Sudah Ditindaklanjuti': return 'bg-emerald-500';
    case 'Selesai': return 'bg-emerald-600';
    default: return 'bg-blueGray-700';
  }
}

/**
 * Helper untuk format waktu (cth: "3 hari yang lalu")
 */
// eslint-disable-next-line no-unused-vars
function formatRelativeTime(dateString) {
  const date = new Date(dateString);
  const now = new Date();
  const seconds = Math.round((now - date) / 1000);
  const minutes = Math.round(seconds / 60);
  const hours = Math.round(minutes / 60);
  const days = Math.round(hours / 24);

  if (seconds < 60) return `${seconds} dtk lalu`;
  if (minutes < 60) return `${minutes} mnt lalu`;
  if (hours < 24) return `${hours} jam lalu`;
  return `${days} hari lalu`;
}
// --- BATAS LOGIKA GALERI ---


onMounted(() => {
  // Ambil semua data yang sudah ada
  pageContentStore.fetchContentBySlug('index');
  contentBlockStore.fetchBlocksByPage('index');
  workProgramStore.fetchWorkPrograms();
  articleStore.fetchLatestArticles();

  // Ambil data baru untuk galeri aspirasi
  fetchPublicAspirations(1);

  // Mulai interval logo
  intervalId = setInterval(() => {
    activeLogoIndex.value = (activeLogoIndex.value + 1) % logos.value.length;
  }, 3000);
});

onUnmounted(() => {
  if (intervalId) {
    clearInterval(intervalId);
  }
});

// (Computed featuredPrograms yang sudah ada)
// eslint-disable-next-line no-unused-vars
const featuredPrograms = computed(() => {
  const allPrograms = workProgramStore.workPrograms;
  const blockData = contentBlockStore.blocks['index-sambutan-ketua'];

  if (!allPrograms || allPrograms.length === 0 || !blockData || !blockData.content.featured_work_program_ids || !Array.isArray(blockData.content.featured_work_program_ids)) {
    return [];
  }
  const featuredIds = blockData.content.featured_work_program_ids;
  return allPrograms.filter(program => featuredIds.includes(program.id));
});
</script>