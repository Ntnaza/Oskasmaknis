<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TeamMember;      // <-- Import model kita
use Illuminate\Support\Str;      // <-- Import generator UUID

class GenerateMemberTokens extends Command
{
    /**
     * Nama dan signature dari console command.
     * Ini adalah perintah yang akan kita ketik di terminal.
     *
     * @var string
     */
    protected $signature = 'members:generate-tokens';

    /**
     * Deskripsi dari console command.
     *
     * @var string
     */
    protected $description = 'Generate QR/member token unik untuk semua anggota yang belum memilikinya';

    /**
     * Jalankan console command.
     */
    public function handle()
    {
        $this->info('Mencari anggota yang belum memiliki token...');

        // 1. Cari semua anggota yang tokennya masih NULL
        $membersToUpdate = TeamMember::whereNull('member_token')->get();

        // 2. Cek apakah ada yang perlu diupdate
        if ($membersToUpdate->isEmpty()) {
            $this->info('Selesai. Semua anggota sudah memiliki token.');
            return 0; // 0 = sukses
        }

        $this->info('Menemukan ' . $membersToUpdate->count() . ' anggota. Memulai proses generate...');
        
        // Buat progress bar
        $progressBar = $this->output->createProgressBar($membersToUpdate->count());
        $progressBar->start();

        // 3. Loop setiap anggota dan buatkan token
        foreach ($membersToUpdate as $member) {
            $member->member_token = (string) Str::uuid();
            $member->save(); // Simpan ke database
            $progressBar->advance(); // Lanjutkan progress bar
        }

        $progressBar->finish();
        $this->newLine(2); // Beri 2 baris spasi
        $this->info('Berhasil! Semua token telah di-generate.');
        
        return 0; // 0 = sukses
    }
}