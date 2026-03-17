<?php

namespace App\Libraries;
use Config\Database;

class SeoHelper
{
    /**
     * Buat schema Product JSON-LD
     */
    public function meta_local_seo($data)
    {
        $html = "
        <title>{$data['name']} | {$data['address']['addressLocality']}</title>
        <meta name='description' content='Temukan {$data['name']} di {$data['address']['addressLocality']}. Hubungi {$data['telephone']} untuk informasi lebih lanjut.'>
        <meta name='geo.region' content='{$data['address']['addressRegion']}' />
        <meta name='geo.placename' content='{$data['address']['addressLocality']}' />
        <meta name='geo.position' content='{$data['geo']['latitude']};{$data['geo']['longitude']}' />
        <meta name='ICBM' content='{$data['geo']['latitude']}, {$data['geo']['longitude']}' />
        ";
        return $html;
    }
    public function local_business_schema($data, $rating, $total_review)
    {
        $rating = $rating ? round($rating, 1):0;
        $reviewCount = $total_review ?? 0;
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "LocalBusiness",
            "name" => $data['name'] ?? '',
            "image" => $data['image'] ?? '',
            "url" => $data['url'] ?? '',
            "telephone" => $data['telephone'] ?? '',
            "address" => array_merge([
                "@type" => "PostalAddress",
                "streetAddress" => "",
                "addressLocality" => "",
                "addressRegion" => "",
                "postalCode" => "",
                "addressCountry" => "ID"
            ], $data['address'] ?? []),
            "geo" => array_merge([
                "@type" => "GeoCoordinates",
                "latitude" => "",
                "longitude" => ""
            ], $data['geo'] ?? []),
            "openingHours" => $data['openingHours'] ?? "Mo-Su 00:00-23:59",
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => number_format((float) $rating, 1, '.', ''),
                "reviewCount" => $reviewCount
            ],
        ];

        return '<script type="application/ld+json">' . 
               json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . 
               '</script>';
    }
    
    public function homeSchema($brand, $rating, $total_review)
    {
        $ratingValue = $rating ? round($rating, 1):0;
        $reviewCount = $total_review ?? 0;


        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => $brand['name'],
            "url" => $brand['url'],
            "logo" => $brand['image'],
            "description" => strip_tags($brand['description']),
            "foundingDate" => "2021",
            "contactPoint" => [
                "@type" => "ContactPoint",
                "telephone" => $brand['telephone'],
                "contactType" => "customer service",
                "areaServed" => "ID",
                "availableLanguage" => ["Indonesian", "English"]
            ],
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $brand['address']['streetAddress'],
                "addressLocality" => $brand['address']['addressLocality'],
                "addressRegion" => $brand['address']['addressRegion'],
                "postalCode" => $brand['address']['postalCode'],
                "addressCountry" => "ID"
            ],
            "sameAs" => $brand['sameAs'] ?? [
                "https://www.facebook.com/profile.php?id=61577764455680&",
                "https://www.instagram.com/kelasbrevet",
                "https://www.youtube.com/@KelasBrevetPajak"
            ]
        ];

        // optional rating brand
        if ($ratingValue > 0 && $reviewCount > 0) {
            $schema["aggregateRating"] = [
                "@type" => "AggregateRating",
                "ratingValue" => number_format($ratingValue, 1, '.', ''),
                "reviewCount" => (int) $reviewCount
            ];
        }

        return '<script type="application/ld+json">' .
            json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) .
            '</script>';
    }
    
    public function productSchema($paket, $rating, $total_review, $reviews)
    {
        $rating = $rating ? round($rating, 1):0;
        $reviewCount = $total_review ?? 0;
        $priceValidUntil = date('Y-m-d', strtotime('+1 year'));
    
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Product",
            "name" => $paket->nama_paket,
            "image" => base_url('assets-landing/images/paket/thumbnails/' .$paket->file),
            "description" => strip_tags($paket->deskripsi),
            "sku" => $paket->idpaket,
            "brand" => [
                "@type" => "Brand",
                "name" => "kelasbrevet"
            ],
            "offers" => [
                "@type" => "Offer",
                "url" => base_url('bimbel/' . $paket->slug),
                "priceCurrency" => "IDR",
                "price" => (string) round($paket->nominal_paket - (($paket->nominal_paket*$paket->diskon)/100)),
                "priceValidUntil" => $priceValidUntil,
                "availability" => "https://schema.org/InStock",
                "itemCondition" => "https://schema.org/NewCondition",
                "seller" => [
                    "@type" => "Organization",
                    "name" => "Akuntanmu Learning Center"
                ],
    
                // ✅ Info pengiriman (shippingDetails)
                "shippingDetails" => [
                    "@type" => "OfferShippingDetails",
                    "shippingRate" => [
                        "@type" => "MonetaryAmount",
                        "value" => "0",
                        "currency" => "IDR"
                    ],
                    "shippingDestination" => [
                        "@type" => "DefinedRegion",
                        "addressCountry" => "ID"
                    ],
                    "deliveryTime" => [
                        "@type" => "ShippingDeliveryTime",
                        "handlingTime" => [
                            "@type" => "QuantitativeValue",
                            "minValue" => 1,
                            "maxValue" => 2,
                            "unitCode" => "DAY"
                        ],
                        "transitTime" => [
                            "@type" => "QuantitativeValue",
                            "minValue" => 1,
                            "maxValue" => 5,
                            "unitCode" => "DAY"
                        ]
                    ]
                ],
    
                // ✅ Info kebijakan pengembalian (hasMerchantReturnPolicy)
                "hasMerchantReturnPolicy" => [
                    "@type" => "MerchantReturnPolicy",
                    "applicableCountry" => "ID",
                    "returnPolicyCategory" => "https://schema.org/MerchantReturnFiniteReturnWindow",
                    "merchantReturnDays" => 7,
                    "returnMethod" => "https://schema.org/ReturnByMail",
                    "returnFees" => "https://schema.org/FreeReturn"
                ]
            ],
    
            // ✅ Rating agregat
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => number_format((float) $rating, 1, '.', ''),
                "reviewCount" => $reviewCount
            ],
    
        ];
       
        // ✅ Tambahkan review jika ada
        if (!empty($reviews)) {
            $schema["review"] = [];
            foreach ($reviews as $rv) {
                $schema["review"][] = [
                    "@type" => "Review",
                    "author" => [
                        "@type" => "Person",
                        "name" => $rv->nama_siswa
                    ],
                    "datePublished" => date('Y-m-d', strtotime($rv->created_at)),
                    "reviewBody" => strip_tags($rv->komentar),
                    "reviewRating" => [
                        "@type" => "Rating",
                        "ratingValue" => round($rv->rating, 1),
                        "bestRating" => "5",
                        "worstRating" => "1"
                    ]
                ];
            }
        }
    
        // ✅ Kembalikan script siap pakai di view
        return '<script type="application/ld+json">' .
            json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) .
            '</script>';
    }




    /**
     * Buat schema ItemList JSON-LD (untuk kategori)
     */
    public function itemListSchema($paket, $title, $desc, $url)
    {
        $db = \Config\Database::connect();
        $items = [];
    
        foreach ($paket as $i => $p) {
    
            $query = $db->table('paket')
                ->join('detail_paket b', 'paket.idpaket=b.idpaket')
                ->join('ujian_master c', 'b.id_ujian=c.id_ujian')
                ->join('review_ujian d', 'c.kode_ujian=d.kode_ujian')
                ->where('paket.slug', $p->slug)
                ->get()
                ->getResultObject();
    
            $reviews = $db->query("
                SELECT q.nama_siswa, q.avatar, d.komentar, d.rating, d.created_at
                FROM review_ujian d
                JOIN ujian_master c ON c.kode_ujian=d.kode_ujian
                JOIN detail_paket b ON b.id_ujian=c.id_ujian
                JOIN paket p ON p.idpaket=b.idpaket
                JOIN siswa q ON q.id_siswa=d.id_siswa
                WHERE d.status = 'A' AND p.slug = ?
            ", [$p->slug])->getResult();
    
            // Hitung rata-rata rating
            $totalRating = 0;
            $jumlahReview = count($query);
            foreach ($query as $r) {
                $totalRating += $r->rating;
            }
            $rataRating = $jumlahReview > 0 ? round($totalRating / $jumlahReview, 1) : 0;
    
            // Struktur Product
            $item = [
                "@type" => "Product",
                "position" => $i + 1,
                "name" => $p->nama_paket,
                "description" => strip_tags($p->deskripsi),
                "image" => base_url('assets-landing/images/paket/thumbnails/' . ($p->file ?? 'default.jpg')),
                "url" => base_url('bimbel/' . $p->slug),
    
                // ✅ Semua penawaran, shipping, dan return policy di dalam "offers"
                "offers" => [
                    "@type" => "Offer",
                    "priceCurrency" => "IDR",
                    "price" => (string) round($p->nominal_paket - (($p->nominal_paket * $p->diskon) / 100)),
                    "priceValidUntil" => date('Y-m-d', strtotime('+1 year')),
                    "availability" => "https://schema.org/InStock",
                    "itemCondition" => "https://schema.org/NewCondition",
                    "seller" => [
                        "@type" => "Organization",
                        "name" => "Akuntanmu Learning Center"
                    ],
    
                    // ✅ shippingDetails
                    "shippingDetails" => [
                        "@type" => "OfferShippingDetails",
                        "shippingRate" => [
                            "@type" => "MonetaryAmount",
                            "value" => "0",
                            "currency" => "IDR"
                        ],
                        "shippingDestination" => [
                            "@type" => "DefinedRegion",
                            "addressCountry" => "ID"
                        ],
                        "deliveryTime" => [
                            "@type" => "ShippingDeliveryTime",
                            "handlingTime" => [
                                "@type" => "QuantitativeValue",
                                "minValue" => 1,
                                "maxValue" => 2,
                                "unitCode" => "DAY"
                            ],
                            "transitTime" => [
                                "@type" => "QuantitativeValue",
                                "minValue" => 1,
                                "maxValue" => 5,
                                "unitCode" => "DAY"
                            ]
                        ]
                    ],
    
                    // ✅ hasMerchantReturnPolicy
                    "hasMerchantReturnPolicy" => [
                        "@type" => "MerchantReturnPolicy",
                        "applicableCountry" => "ID",
                        "returnPolicyCategory" => "https://schema.org/MerchantReturnFiniteReturnWindow",
                        "merchantReturnDays" => 7,
                        "returnMethod" => "https://schema.org/ReturnByMail",
                        "returnFees" => "https://schema.org/FreeReturn"
                    ]
                ],
    
                // ✅ Rating agregat
                "aggregateRating" => [
                    "@type" => "AggregateRating",
                    "ratingValue" => number_format((float)$rataRating, 1, '.', ''),
                    "reviewCount" => $jumlahReview
                ]
            ];
    
            // ✅ Tambahkan review jika ada
            if (!empty($reviews)) {
                $item["review"] = [];
                foreach ($reviews as $rv) {
                    $item["review"][] = [
                        "@type" => "Review",
                        "author" => [
                            "@type" => "Person",
                            "name" => $rv->nama_siswa
                        ],
                        "datePublished" => date('Y-m-d', strtotime($rv->created_at)),
                        "reviewBody" => strip_tags($rv->komentar),
                        "reviewRating" => [
                            "@type" => "Rating",
                            "ratingValue" => round($rv->rating, 1),
                            "bestRating" => "5",
                            "worstRating" => "1"
                        ]
                    ];
                }
            }
    
            $items[] = $item;
        }
    
        // ✅ Bungkus seluruh produk menjadi ItemList
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "ItemList",
            "name" => $title,
            "description" => $desc,
            "url" => $url,
            "itemListElement" => $items
        ];
    
        return '<script type="application/ld+json">' .
            json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) .
            '</script>';
    }



    public function breadcrumbSchema($items)
    {
        $position = 1;
        $itemList = [];

        foreach ($items as $name => $url) {
            $itemList[] = [
                "@type" => "ListItem",
                "position" => $position++,
                "name" => $name,
                "item" => $url
            ];
        }

        $schema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $itemList
        ];

        return '<script type="application/ld+json">' .
            json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
            '</script>';
    }
}
