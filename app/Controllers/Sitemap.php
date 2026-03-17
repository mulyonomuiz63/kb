<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\kategoriModel;
use CodeIgniter\Controller;
use CodeIgniter\Cache\CacheInterface;

class Sitemap extends Controller
{
    // File sitemap akan disimpan di sini
    protected $filePath;
    protected $db;

    public function __construct()
    {
        $this->filePath = ROOTPATH . 'sitemap.xml';
        $this->baseUrl = base_url();
        $this->db = \Config\Database::connect();
    }

    /**
     * Fungsi utama: tampilkan sitemap.xml
     * Jika file belum ada atau sudah lama -> regenerasi otomatis
     */
    public function serve()
    {
        // Jika file belum ada atau lebih dari 6 jam, buat baru
        if (!file_exists($this->filePath) || (time() - filemtime($this->filePath)) > 6 * 3600) {
            $this->generate();
        }

        $this->pingSearchEngines();

        // Kirim file sitemap.xml ke browser
        return $this->response
            ->setHeader('Content-Type', 'application/xml')
            ->setBody(file_get_contents($this->filePath));
    }

    /**
     * Generate sitemap baru dan simpan ke public/sitemap.xml
     */
    public function generate()
    {
        $kategori = $this->db->query("select DISTINCT slug_kategori from kategori_artikel")->getResult();
        $artikel = $this->db->query("select DISTINCT slug_judul from artikel")->getResult();
        $paket =$this->db->query("select DISTINCT slug from paket where status='1'")->getResult();
        $twibbon =$this->db->query("select DISTINCT url from twibbon")->getResult();
        
        $baseUrl = rtrim(base_url(), '/') . '/';
    
      
    
        // Mulai XML
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    
        // Halaman utama
        $xml .= '<url>
            <loc>' . $baseUrl . '</loc>
            <priority>1.0</priority>
            <changefreq>daily</changefreq>
        </url>' . PHP_EOL;
    
        // Kategori unik
        foreach ($kategori as $kat) {
            $xml .= '<url>
                <loc>' . $baseUrl . 'kategori/' . ($kat->slug_kategori) . '</loc>
                <lastmod>' . date('Y-m-d') . '</lastmod>
                <priority>0.8</priority>
            </url>' . PHP_EOL;
        }
    
        // artikel
        foreach ($artikel as $ar) {
            $xml .= '<url>
                <loc>' . $baseUrl . 'artikel/' . ($ar->slug_judul) . '</loc>
                <lastmod>' . date('Y-m-d') . '</lastmod>
                <priority>0.7</priority>
            </url>' . PHP_EOL;
        }
        
        //untuk paket
        foreach ($paket as $pak) {
            $xml .= '<url>
                <loc>' . $baseUrl . 'bimbel/' . ($pak->slug) . '</loc>
                <lastmod>' . date('Y-m-d') . '</lastmod>
                <priority>0.7</priority>
            </url>' . PHP_EOL;
        }
        
        //untuk twibbon
        foreach ($twibbon as $twi) {
            $xml .= '<url>
                <loc>' . $baseUrl . 'twibbon/' . ($twi->url) . '</loc>
                <lastmod>' . date('Y-m-d') . '</lastmod>
                <priority>0.7</priority>
            </url>' . PHP_EOL;
        }
    
        // Halaman tambahan
        $extraPages = ['tentangkami', 'pelatihan', 'penilaian', 'testimoni', 'artikel', 'jadwal', 'galeri', 'media-kelasbrevet', 'siap-kerja', 'twibbon', 'term', 'privasi', 'quiz'];
        foreach ($extraPages as $page) {
            $xml .= '<url>
                <loc>' . $baseUrl . $page . '</loc>
                <priority>0.5</priority>
            </url>' . PHP_EOL;
        }
    
        $xml .= '</urlset>';
    
        // Simpan file ke public/sitemap.xml
        file_put_contents(FCPATH . 'sitemap.xml', $xml);
    
        return $this->response
            ->setHeader('Content-Type', 'application/xml')
            ->setBody($xml);
    }


    // ✅ Auto Ping Search Engines
    private function pingSearchEngines()
    {
        $sitemapUrl = urlencode($this->baseUrl . 'sitemap.xml');

        $engines = [
            'Google' => 'https://www.google.com/ping?sitemap=' . $sitemapUrl,
            'Bing'   => 'https://www.bing.com/ping?sitemap=' . $sitemapUrl,
            'Yandex' => 'https://yandex.com/ping?sitemap=' . $sitemapUrl,
        ];

        foreach ($engines as $name => $url) {
            try {
                $context = stream_context_create(['http' => ['timeout' => 5]]);
                file_get_contents($url, false, $context);
            } catch (\Exception $e) {
                log_message('error', 'Gagal ping ' . $name . ': ' . $e->getMessage());
            }
        }
    }

}
