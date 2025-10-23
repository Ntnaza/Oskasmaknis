<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ContentBlockController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['page_slug' => 'required|string']);
        $blocks = ContentBlock::where('page_slug', $request->page_slug)->get();
        return response()->json(['data' => $blocks]);
    }

    public function show(string $key)
    {
        Log::info('Mencari ContentBlock dengan section_key: ' . $key);
        $block = ContentBlock::where('section_key', $key)->firstOrFail();
        Log::info('ContentBlock ditemukan:', ['id' => $block->id]);
        return response()->json(['data' => $block]);
    }

    public function update(Request $request, string $key)
    {
        Log::info('Memulai proses update untuk key: ' . $key);

        $block = ContentBlock::where('section_key', $key)->firstOrFail();

        $contentData = json_decode($request->input('content'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Gagal decode JSON content:', ['json_input' => $request->input('content')]);
            return response()->json(['message' => 'Invalid content data format.'], 400);
        }
        Log::info('Data content berhasil di-decode.');

        $newContent = $contentData;

        if ($request->hasFile('image_file')) {
            Log::info('File image_file DITEMUKAN untuk key: ' . $key);

            if (isset($block->content['image_url']) && $block->content['image_url'] && Storage::disk('public')->exists($block->content['image_url'])) {
                 Log::info('Menghapus image_file lama: ' . $block->content['image_url']);
                Storage::disk('public')->delete($block->content['image_url']);
            }

            try {
                $path = $request->file('image_file')->store('promo_images', 'public');
                if ($path) {
                    Log::info('File image_file BERHASIL disimpan. Path: ' . $path);
                    $newContent['image_url'] = $path;
                } else {
                    Log::error('Penyimpanan image_file GAGAL (store return false/null).');
                }
            } catch (\Exception $e) {
                Log::error('Error saat menyimpan image_file: ' . $e->getMessage());
                return response()->json(['message' => 'Terjadi error saat menyimpan gambar.'], 500);
            }
        } else {
             Log::warning('File image_file TIDAK DITEMUKAN dalam request.');
             if (isset($block->content['image_url'])) {
                 $newContent['image_url'] = $block->content['image_url'];
             } else {
                  $newContent['image_url'] = null;
             }
        }

        $block->content = $newContent;
        $block->save();
        Log::info('ContentBlock untuk key ' . $key . ' berhasil disimpan.');

        return response()->json([
            'message' => 'Content block updated successfully!',
            'data' => $block
        ]);
    }

    public function updateBulk(Request $request)
    {
        Log::info('Memulai proses updateBulk.');

        $blocksData = json_decode($request->input('blocks'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Gagal decode JSON blocks:', ['json_input' => $request->input('blocks')]);
            return response()->json(['message' => 'Invalid block data format.'], 400);
        }

        Log::info('Data blocks berhasil di-decode.', ['count' => count($blocksData)]);

        foreach ($blocksData as $blockData) {
            $block = ContentBlock::find($blockData['id']);
            if (!$block) {
                Log::warning('ContentBlock tidak ditemukan untuk ID:', ['id' => $blockData['id']]);
                continue;
            }

            Log::info('Memproses block ID: ' . $block->id . ', section_key: ' . $block->section_key);

            $newContent = array_merge($block->content, $blockData['content']);

            if ($block->section_key === 'index-sambutan-ketua') {
                Log::info('Memproses blok index-sambutan-ketua.');
                if ($request->hasFile('osisFile')) {
                    Log::info('File osisFile DITEMUKAN.');
                    if (isset($block->content['ketua_osis_image_path']) && $block->content['ketua_osis_image_path'] && Storage::disk('public')->exists($block->content['ketua_osis_image_path'])) {
                        Log::info('Menghapus osisFile lama: ' . $block->content['ketua_osis_image_path']);
                        Storage::disk('public')->delete($block->content['ketua_osis_image_path']);
                    }
                    try {
                        $path = $request->file('osisFile')->store('leaders', 'public');
                        if($path) {
                            Log::info('File osisFile BERHASIL disimpan. Path: ' . $path);
                            $newContent['ketua_osis_image_path'] = $path;
                        } else {
                             Log::error('Penyimpanan osisFile GAGAL (store return false/null).');
                        }
                    } catch (\Exception $e) {
                         Log::error('Error saat menyimpan osisFile: ' . $e->getMessage());
                    }
                } else {
                     Log::warning('File osisFile TIDAK DITEMUKAN dalam request.');
                }

                if ($request->hasFile('mpkFile')) {
                    Log::info('File mpkFile DITEMUKAN.');
                     if (isset($block->content['ketua_mpk_image_path']) && $block->content['ketua_mpk_image_path'] && Storage::disk('public')->exists($block->content['ketua_mpk_image_path'])) {
                        Log::info('Menghapus mpkFile lama: ' . $block->content['ketua_mpk_image_path']);
                        Storage::disk('public')->delete($block->content['ketua_mpk_image_path']);
                    }
                    try {
                        $path = $request->file('mpkFile')->store('leaders', 'public');
                         if($path) {
                            Log::info('File mpkFile BERHASIL disimpan. Path: ' . $path);
                            $newContent['ketua_mpk_image_path'] = $path;
                        } else {
                             Log::error('Penyimpanan mpkFile GAGAL (store return false/null).');
                        }
                    } catch (\Exception $e) {
                         Log::error('Error saat menyimpan mpkFile: ' . $e->getMessage());
                    }
                } else {
                     Log::warning('File mpkFile TIDAK DITEMUKAN dalam request.');
                }
            }
            elseif ($block->section_key === 'index-pembina') {
                Log::info('Memproses blok index-pembina.');
                if ($request->hasFile('pembinaFile')) {
                    Log::info('File pembinaFile DITEMUKAN.');
                    if (isset($block->content['pembina_image_path']) && $block->content['pembina_image_path'] && Storage::disk('public')->exists($block->content['pembina_image_path'])) {
                        Log::info('Menghapus pembinaFile lama: ' . $block->content['pembina_image_path']);
                        Storage::disk('public')->delete($block->content['pembina_image_path']);
                    }
                    try {
                        $path = $request->file('pembinaFile')->store('pembina', 'public');
                        if ($path) {
                            Log::info('File pembinaFile BERHASIL disimpan. Path: ' . $path);
                            $newContent['pembina_image_path'] = $path;
                            Log::info('Path ditambahkan ke newContent:', $newContent);
                        } else {
                            Log::error('Penyimpanan pembinaFile GAGAL (store return false/null).');
                        }
                    } catch (\Exception $e) {
                        Log::error('Error saat menyimpan pembinaFile: ' . $e->getMessage());
                    }
                } else {
                    Log::warning('File pembinaFile TIDAK DITEMUKAN dalam request.');
                }
            }

            Log::info('Konten final sebelum save untuk block ID ' . $block->id . ':', $newContent);
            $block->content = $newContent;
            $block->save();
            Log::info('Block ID ' . $block->id . ' berhasil disimpan.');
        }

        Log::info('Proses updateBulk selesai.');
        return response()->json(['message' => 'Content blocks updated successfully']);
    }
}