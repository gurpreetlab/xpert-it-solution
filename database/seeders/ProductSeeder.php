<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->catalog() as $categoryName => $categoryData) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName]
            );

            $brandIds = $this->createBrands($categoryData['brands']);

            foreach ($categoryData['types'] as $products) {
                foreach ($products as $productData) {
                    $this->createProduct($category, $brandIds, $productData);
                }
            }
        }

        $this->refreshCounts();
    }

    /**
     * @param  string[]  $brandNames
     * @return Collection<string, int> brand name => brand id
     */
    private function createBrands(array $brandNames): Collection
    {
        return collect($brandNames)->mapWithKeys(function (string $brandName) {
            $brand = Brand::updateOrCreate(
                ['slug' => Str::slug($brandName)],
                ['name' => $brandName]
            );

            return [$brandName => $brand->id];
        });
    }

    private function createProduct(Category $category, Collection $brandIds, array $data): void
    {
        $product = Product::updateOrCreate(
            ['slug' => Str::slug($data['name'])],
            [
                'category_id' => $category->id,
                'brand_id' => isset($data['brand']) ? $brandIds->get($data['brand']) : null,
                'name' => $data['name'],
                'sku' => $data['sku'],
                'hsn_code' => $data['hsn_code'],
                'mrp' => $data['mrp'],
                'purchase_price' => $data['purchase_price'],
                'sale_price' => $data['sale_price'],
                'stock' => $data['stock'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'is_featured' => $data['is_featured'] ?? false,
                'is_active' => true,
            ]
        );

        // Replace specs each run so re-seeding stays in sync with the
        // catalog below rather than accumulating duplicates.
        $product->specifications()->delete();

        $sortOrder = 0;

        foreach ($data['specs'] as $key => $value) {
            $product->specifications()->create([
                'key' => $key,
                'value' => $value,
                'sort_order' => $sortOrder++,
            ]);
        }
    }

    private function refreshCounts(): void
    {
        Category::all()->each(function (Category $category) {
            $category->forceFill(['products_count' => $category->products()->count()])->saveQuietly();
        });

        Brand::all()->each(function (Brand $brand) {
            $brand->forceFill(['products_count' => $brand->products()->count()])->saveQuietly();
        });
    }

    private function catalog(): array
    {
        return [
            'Networking' => [
                'brands' => ['TP-Link', 'D-Link', 'Netgear', 'Tenda', 'Mercusys'],
                'types' => [
                    'Router' => [
                        [
                            'brand' => 'TP-Link',
                            'name' => 'TP-Link Archer C6 AC1200 Dual-Band Wi-Fi Router',
                            'sku' => 'NET-RTR-01',
                            'hsn_code' => '8517',
                            'mrp' => 3299, 'purchase_price' => 2100, 'sale_price' => 2799, 'stock' => 40,
                            'short_description' => 'Dual-band AC1200 router with 4 external antennas for wide Wi-Fi coverage.',
                            'description' => 'Combined wireless speeds of up to 1200 Mbps across 2.4GHz and 5GHz bands make this a solid choice for HD streaming, gaming and busy multi-device homes.',
                            'specs' => [
                                'Wireless Standard' => '802.11ac',
                                'Speed' => 'Up to 1200 Mbps',
                                'LAN Ports' => '4 x Gigabit',
                                'Antenna' => '4 External Fixed',
                            ],
                        ],
                        [
                            'brand' => 'D-Link',
                            'name' => 'D-Link DIR-825 AC1200 Wireless Router',
                            'sku' => 'NET-RTR-02',
                            'hsn_code' => '8517',
                            'mrp' => 2999, 'purchase_price' => 1900, 'sale_price' => 2499, 'stock' => 35,
                            'short_description' => 'Reliable dual-band router for everyday home and small-office use.',
                            'description' => 'Stable dual-band connectivity with parental controls and a simple setup wizard, suited to small households and offices.',
                            'specs' => [
                                'Wireless Standard' => '802.11ac',
                                'Speed' => 'Up to 1200 Mbps',
                                'LAN Ports' => '4 x Fast Ethernet',
                                'Antenna' => '2 External',
                            ],
                        ],
                        [
                            'brand' => 'Netgear',
                            'name' => 'Netgear Nighthawk AX1800 Wi-Fi 6 Router',
                            'sku' => 'NET-RTR-03',
                            'hsn_code' => '8517',
                            'mrp' => 8999, 'purchase_price' => 6200, 'sale_price' => 7499, 'stock' => 15,
                            'is_featured' => true,
                            'short_description' => 'Next-gen Wi-Fi 6 router built for high-density, low-latency networks.',
                            'description' => 'Supports more simultaneous connections with lower latency, ideal for smart homes running multiple streaming and gaming devices at once.',
                            'specs' => [
                                'Wireless Standard' => '802.11ax (Wi-Fi 6)',
                                'Speed' => 'Up to 1800 Mbps',
                                'LAN Ports' => '4 x Gigabit',
                                'Antenna' => '4 Internal',
                            ],
                        ],
                    ],
                    'Switch' => [
                        [
                            'brand' => 'TP-Link',
                            'name' => 'TP-Link TL-SG1008D 8-Port Gigabit Desktop Switch',
                            'sku' => 'NET-SW-01',
                            'hsn_code' => '8517',
                            'mrp' => 1899, 'purchase_price' => 1200, 'sale_price' => 1599, 'stock' => 50,
                            'short_description' => 'Unmanaged 8-port Gigabit switch for expanding a wired network.',
                            'description' => 'Plug-and-play Gigabit switch that adds 8 wired ports to any network without any configuration required.',
                            'specs' => [
                                'Ports' => '8 x Gigabit',
                                'Switching Capacity' => '16 Gbps',
                                'Type' => 'Unmanaged',
                                'Mounting' => 'Desktop',
                            ],
                        ],
                        [
                            'brand' => 'D-Link',
                            'name' => 'D-Link DES-1024D 24-Port Fast Ethernet Switch',
                            'sku' => 'NET-SW-02',
                            'hsn_code' => '8517',
                            'mrp' => 4499, 'purchase_price' => 3100, 'sale_price' => 3899, 'stock' => 12,
                            'short_description' => '24-port switch for larger offices and server rooms.',
                            'description' => 'High port-density unmanaged switch designed for offices needing to connect many wired workstations or CCTV devices.',
                            'specs' => [
                                'Ports' => '24 x Fast Ethernet',
                                'Switching Capacity' => '8.8 Gbps',
                                'Type' => 'Unmanaged',
                                'Mounting' => 'Rack/Desktop',
                            ],
                        ],
                    ],
                    'LAN Cable' => [
                        [
                            'brand' => 'D-Link',
                            'name' => 'D-Link Cat6 UTP LAN Cable 305m Box',
                            'sku' => 'NET-LAN-01',
                            'hsn_code' => '8544',
                            'mrp' => 5499, 'purchase_price' => 3800, 'sale_price' => 4699, 'stock' => 20,
                            'short_description' => 'Bulk Cat6 UTP cable for structured network cabling.',
                            'description' => 'Solid-core Cat6 cable rated for Gigabit speeds, supplied in a pull box for easy in-wall installation.',
                            'specs' => [
                                'Category' => 'Cat6',
                                'Conductor' => 'Solid Copper',
                                'Length' => '305m',
                                'Shielding' => 'UTP',
                            ],
                        ],
                        [
                            'brand' => 'Tenda',
                            'name' => 'Tenda Cat5e Patch Cable 3m',
                            'sku' => 'NET-LAN-02',
                            'hsn_code' => '8544',
                            'mrp' => 199, 'purchase_price' => 90, 'sale_price' => 149, 'stock' => 150,
                            'short_description' => 'Ready-made 3m patch cable for connecting devices to a switch or router.',
                            'description' => 'Factory-crimped Cat5e patch cord, tested for continuity and suitable for Gigabit Ethernet.',
                            'specs' => [
                                'Category' => 'Cat5e',
                                'Length' => '3m',
                                'Connector' => 'RJ45',
                                'Shielding' => 'UTP',
                            ],
                        ],
                    ],
                    'Wi-Fi Dongle' => [
                        [
                            'brand' => 'TP-Link',
                            'name' => 'TP-Link TL-WN823N 300Mbps Wireless USB Adapter',
                            'sku' => 'NET-WFD-01',
                            'hsn_code' => '8517',
                            'mrp' => 699, 'purchase_price' => 420, 'sale_price' => 599, 'stock' => 80,
                            'short_description' => 'Compact USB Wi-Fi adapter for desktops without built-in wireless.',
                            'description' => 'Mini USB adapter that adds 300 Mbps wireless connectivity to any desktop PC with a free USB port.',
                            'specs' => [
                                'Wireless Standard' => '802.11n',
                                'Speed' => '300 Mbps',
                                'Interface' => 'USB 2.0',
                                'Size' => 'Nano',
                            ],
                        ],
                        [
                            'brand' => 'Mercusys',
                            'name' => 'Mercusys MW300UM Mini Wireless USB Adapter',
                            'sku' => 'NET-WFD-02',
                            'hsn_code' => '8517',
                            'mrp' => 549, 'purchase_price' => 320, 'sale_price' => 449, 'stock' => 60,
                            'short_description' => 'Ultra-compact 300Mbps Wi-Fi USB dongle.',
                            'description' => 'Nearly flush-fitting USB adapter offering reliable 2.4GHz wireless connectivity for laptops and desktops.',
                            'specs' => [
                                'Wireless Standard' => '802.11n',
                                'Speed' => '300 Mbps',
                                'Interface' => 'USB 2.0',
                                'Size' => 'Nano',
                            ],
                        ],
                    ],
                ],
            ],

            'CCTV & Security' => [
                'brands' => ['Hikvision', 'CP Plus', 'Dahua', 'Godrej Security Solutions'],
                'types' => [
                    'NVR' => [
                        [
                            'brand' => 'Hikvision',
                            'name' => 'Hikvision DS-7608NI-K2 8-Channel NVR',
                            'sku' => 'CCTV-NVR-01',
                            'hsn_code' => '8521',
                            'mrp' => 12999, 'purchase_price' => 8900, 'sale_price' => 10999, 'stock' => 10,
                            'short_description' => '8-channel NVR with 2 SATA bays for IP camera recording.',
                            'description' => 'Supports up to 8 IP cameras at up to 8MP resolution, with dual SATA bays for long-duration local storage.',
                            'specs' => [
                                'Channels' => '8',
                                'Resolution Support' => 'Up to 8MP',
                                'Storage Bays' => '2 x SATA',
                                'HDMI Output' => 'Yes',
                            ],
                        ],
                        [
                            'brand' => 'CP Plus',
                            'name' => 'CP Plus CP-UNR-4K2S2 4-Channel NVR',
                            'sku' => 'CCTV-NVR-02',
                            'hsn_code' => '8521',
                            'mrp' => 7499, 'purchase_price' => 5100, 'sale_price' => 6299, 'stock' => 18,
                            'short_description' => 'Budget-friendly 4-channel NVR for small IP camera setups.',
                            'description' => 'Compact NVR suited to homes and small shops running up to 4 IP cameras, with mobile app remote viewing.',
                            'specs' => [
                                'Channels' => '4',
                                'Resolution Support' => 'Up to 4MP',
                                'Storage Bays' => '1 x SATA',
                                'Mobile App' => 'Yes',
                            ],
                        ],
                    ],
                    'DVR' => [
                        [
                            'brand' => 'Dahua',
                            'name' => 'Dahua DH-XVR5108HS 8-Channel DVR',
                            'sku' => 'CCTV-DVR-01',
                            'hsn_code' => '8521',
                            'mrp' => 9999, 'purchase_price' => 6900, 'sale_price' => 8499, 'stock' => 14,
                            'short_description' => '8-channel Penta-brid DVR supporting analog and IP cameras.',
                            'description' => 'Penta-brid design accepts HDCVI, AHD, TVI, CVBS and IP camera inputs on the same recorder, easing mixed-camera upgrades.',
                            'specs' => [
                                'Channels' => '8',
                                'Camera Support' => 'HDCVI/AHD/TVI/CVBS/IP',
                                'Resolution Support' => 'Up to 5MP',
                                'Storage Bays' => '1 x SATA',
                            ],
                        ],
                        [
                            'brand' => 'CP Plus',
                            'name' => 'CP Plus CP-UVR-0801E1-V3 8-Channel DVR',
                            'sku' => 'CCTV-DVR-02',
                            'hsn_code' => '8521',
                            'mrp' => 6999, 'purchase_price' => 4700, 'sale_price' => 5999, 'stock' => 22,
                            'short_description' => 'Entry-level 8-channel DVR for standard-definition analog cameras.',
                            'description' => 'Reliable, easy-to-configure DVR ideal for shops and homes running standard HD analog camera setups.',
                            'specs' => [
                                'Channels' => '8',
                                'Camera Support' => 'HD Analog',
                                'Resolution Support' => 'Up to 2MP',
                                'Storage Bays' => '1 x SATA',
                            ],
                        ],
                    ],
                    'Camera' => [
                        [
                            'brand' => 'Hikvision',
                            'name' => 'Hikvision DS-2CE1AC0T-IRP 1MP Dome Camera',
                            'sku' => 'CCTV-CAM-01',
                            'hsn_code' => '8525',
                            'mrp' => 1299, 'purchase_price' => 750, 'sale_price' => 1099, 'stock' => 60,
                            'short_description' => 'Indoor dome camera with night vision for basic surveillance.',
                            'description' => 'Compact HD dome camera with up to 20m IR night vision, well suited to indoor monitoring of shops and homes.',
                            'specs' => [
                                'Resolution' => '1MP',
                                'Lens' => '3.6mm Fixed',
                                'Night Vision' => 'Up to 20m',
                                'Camera Type' => 'Dome',
                            ],
                        ],
                        [
                            'brand' => 'CP Plus',
                            'name' => 'CP Plus CP-UNC-TA21PL3 2MP Bullet IP Camera',
                            'sku' => 'CCTV-CAM-02',
                            'hsn_code' => '8525',
                            'mrp' => 2799, 'purchase_price' => 1750, 'sale_price' => 2399, 'stock' => 45,
                            'is_featured' => true,
                            'short_description' => 'Weatherproof 2MP bullet IP camera for outdoor surveillance.',
                            'description' => 'Full HD IP bullet camera rated for outdoor use, with smart IR night vision and motion-based alerts.',
                            'specs' => [
                                'Resolution' => '2MP',
                                'Lens' => '3.6mm Fixed',
                                'Night Vision' => 'Up to 30m',
                                'Camera Type' => 'Bullet (Outdoor)',
                            ],
                        ],
                        [
                            'brand' => 'Dahua',
                            'name' => 'Dahua IPC-HFW1230S 2MP IR Bullet Camera',
                            'sku' => 'CCTV-CAM-03',
                            'hsn_code' => '8525',
                            'mrp' => 2999, 'purchase_price' => 1900, 'sale_price' => 2599, 'stock' => 38,
                            'short_description' => '2MP IP bullet camera with IP67-rated weatherproof housing.',
                            'description' => 'Delivers clear full HD footage day and night, with a rugged housing designed to withstand outdoor conditions.',
                            'specs' => [
                                'Resolution' => '2MP',
                                'Lens' => '2.8mm Fixed',
                                'Night Vision' => 'Up to 30m',
                                'Weather Rating' => 'IP67',
                            ],
                        ],
                    ],
                    'Power Supply' => [
                        [
                            'brand' => 'CP Plus',
                            'name' => 'CP Plus 12V 5A CCTV SMPS Power Supply (8 Channel)',
                            'sku' => 'CCTV-PSU-01',
                            'hsn_code' => '8504',
                            'mrp' => 899, 'purchase_price' => 520, 'sale_price' => 749, 'stock' => 40,
                            'short_description' => '8-way distribution SMPS for powering multiple CCTV cameras.',
                            'description' => 'Single 12V 5A power supply with 8 individually fused outputs, simplifying camera installations with a shared power point.',
                            'specs' => [
                                'Output' => '12V DC',
                                'Current' => '5A',
                                'Channels' => '8',
                                'Protection' => 'Fused Outputs',
                            ],
                        ],
                    ],
                    'BNC Connector' => [
                        [
                            'name' => 'Generic BNC Male Connector (Pack of 10)',
                            'sku' => 'CCTV-BNC-01',
                            'hsn_code' => '8536',
                            'mrp' => 199, 'purchase_price' => 90, 'sale_price' => 149, 'stock' => 200,
                            'short_description' => 'Crimp-style BNC male connectors for coaxial CCTV cabling.',
                            'description' => 'Standard male BNC connectors used to terminate coaxial cable runs for analog camera installations.',
                            'specs' => [
                                'Type' => 'BNC Male',
                                'Termination' => 'Crimp',
                                'Pack Size' => '10',
                                'Compatible Cable' => 'RG59/RG6',
                            ],
                        ],
                    ],
                    'Coaxial Cable' => [
                        [
                            'brand' => 'CP Plus',
                            'name' => 'CP Plus 3+1 Coaxial CCTV Cable 90m Coil',
                            'sku' => 'CCTV-COAX-01',
                            'hsn_code' => '8544',
                            'mrp' => 2499, 'purchase_price' => 1600, 'sale_price' => 2099, 'stock' => 25,
                            'short_description' => 'Combined video and power coaxial cable for analog CCTV runs.',
                            'description' => '3+1 cable carries both the video signal and camera power in a single jacket, reducing installation time.',
                            'specs' => [
                                'Type' => '3+1 Coaxial',
                                'Length' => '90m',
                                'Conductor' => 'Copper',
                                'Use' => 'Analog CCTV',
                            ],
                        ],
                    ],
                ],
            ],

            'Storage' => [
                'brands' => ['Western Digital', 'Seagate', 'Samsung', 'Kingston', 'SanDisk'],
                'types' => [
                    'Internal HDD' => [
                        [
                            'brand' => 'Western Digital',
                            'name' => 'Western Digital Blue 1TB 3.5" SATA HDD',
                            'sku' => 'STG-HDD-01',
                            'hsn_code' => '8471',
                            'mrp' => 3699, 'purchase_price' => 2600, 'sale_price' => 3299, 'stock' => 45,
                            'short_description' => 'Reliable 1TB desktop hard drive for everyday storage.',
                            'description' => '7200 RPM SATA drive offering a solid balance of capacity, performance and price for desktop PCs.',
                            'specs' => [
                                'Capacity' => '1TB',
                                'Interface' => 'SATA III',
                                'Form Factor' => '3.5-inch',
                                'RPM' => '7200',
                            ],
                        ],
                        [
                            'brand' => 'Seagate',
                            'name' => 'Seagate Surveillance 2TB 3.5" SATA HDD',
                            'sku' => 'STG-HDD-02',
                            'hsn_code' => '8471',
                            'mrp' => 5999, 'purchase_price' => 4200, 'sale_price' => 5299, 'stock' => 30,
                            'short_description' => 'Purpose-built HDD for 24/7 CCTV recording workloads.',
                            'description' => 'Engineered for continuous read/write cycles in DVRs and NVRs, with firmware tuned for surveillance video streams.',
                            'specs' => [
                                'Capacity' => '2TB',
                                'Interface' => 'SATA III',
                                'Form Factor' => '3.5-inch',
                                'Workload' => '24x7 Surveillance',
                            ],
                        ],
                        [
                            'brand' => 'Western Digital',
                            'name' => 'Western Digital Purple 4TB 3.5" Surveillance HDD',
                            'sku' => 'STG-HDD-03',
                            'hsn_code' => '8471',
                            'mrp' => 9999, 'purchase_price' => 7300, 'sale_price' => 8799, 'stock' => 20,
                            'is_featured' => true,
                            'short_description' => 'High-capacity surveillance-grade drive for multi-camera NVR setups.',
                            'description' => 'Built for always-on video recording with firmware tuned to reduce frame loss across multiple camera streams.',
                            'specs' => [
                                'Capacity' => '4TB',
                                'Interface' => 'SATA III',
                                'Form Factor' => '3.5-inch',
                                'Workload' => '24x7 Surveillance',
                            ],
                        ],
                    ],
                    'SSD' => [
                        [
                            'brand' => 'Samsung',
                            'name' => 'Samsung 970 EVO Plus 500GB NVMe SSD',
                            'sku' => 'STG-SSD-01',
                            'hsn_code' => '8471',
                            'mrp' => 6499, 'purchase_price' => 4600, 'sale_price' => 5799, 'stock' => 35,
                            'short_description' => 'High-speed NVMe SSD for a snappier system boot and load times.',
                            'description' => 'PCIe NVMe drive delivering sequential speeds far beyond SATA SSDs, ideal for OS drives and creative workloads.',
                            'specs' => [
                                'Capacity' => '500GB',
                                'Interface' => 'NVMe PCIe Gen3',
                                'Form Factor' => 'M.2 2280',
                                'Read Speed' => 'Up to 3500 MB/s',
                            ],
                        ],
                        [
                            'brand' => 'Kingston',
                            'name' => 'Kingston A400 240GB SATA SSD',
                            'sku' => 'STG-SSD-02',
                            'hsn_code' => '8471',
                            'mrp' => 1999, 'purchase_price' => 1300, 'sale_price' => 1749, 'stock' => 60,
                            'short_description' => 'Budget SATA SSD to replace a slow hard drive.',
                            'description' => 'An affordable upgrade path from a mechanical HDD, noticeably speeding up boot times and application loading.',
                            'specs' => [
                                'Capacity' => '240GB',
                                'Interface' => 'SATA III',
                                'Form Factor' => '2.5-inch',
                                'Read Speed' => 'Up to 500 MB/s',
                            ],
                        ],
                        [
                            'brand' => 'Western Digital',
                            'name' => 'WD Green 512GB SATA SSD',
                            'sku' => 'STG-SSD-03',
                            'hsn_code' => '8471',
                            'mrp' => 2799, 'purchase_price' => 1900, 'sale_price' => 2399, 'stock' => 40,
                            'short_description' => 'Efficient SATA SSD for laptops and everyday desktops.',
                            'description' => 'Balances capacity and price for users upgrading from a hard drive without needing NVMe-class speeds.',
                            'specs' => [
                                'Capacity' => '512GB',
                                'Interface' => 'SATA III',
                                'Form Factor' => '2.5-inch',
                                'Read Speed' => 'Up to 545 MB/s',
                            ],
                        ],
                    ],
                    'Memory Card' => [
                        [
                            'brand' => 'SanDisk',
                            'name' => 'SanDisk Ultra 32GB microSDHC Class 10',
                            'sku' => 'STG-MEM-01',
                            'hsn_code' => '8523',
                            'mrp' => 499, 'purchase_price' => 280, 'sale_price' => 399, 'stock' => 100,
                            'short_description' => 'Everyday microSD card for phones, cameras and dash-cams.',
                            'description' => 'Class 10 rated card offering dependable read/write speeds for photos, video and app storage.',
                            'specs' => [
                                'Capacity' => '32GB',
                                'Speed Class' => 'Class 10 / UHS-I',
                                'Card Type' => 'microSDHC',
                                'Read Speed' => 'Up to 100 MB/s',
                            ],
                        ],
                        [
                            'brand' => 'Samsung',
                            'name' => 'Samsung EVO Plus 64GB microSDXC',
                            'sku' => 'STG-MEM-02',
                            'hsn_code' => '8523',
                            'mrp' => 799, 'purchase_price' => 480, 'sale_price' => 699, 'stock' => 70,
                            'short_description' => 'Higher-capacity microSD card for 4K-capable devices.',
                            'description' => 'Rated for 4K UHD video recording, with waterproof and temperature-proof construction for action cameras.',
                            'specs' => [
                                'Capacity' => '64GB',
                                'Speed Class' => 'Class 10 / UHS-I / U3',
                                'Card Type' => 'microSDXC',
                                'Read Speed' => 'Up to 130 MB/s',
                            ],
                        ],
                    ],
                ],
            ],

            'Computer Peripherals' => [
                'brands' => ['Logitech', 'HP', 'Dell', 'Zebronics', 'Frontech'],
                'types' => [
                    'Keyboard' => [
                        [
                            'brand' => 'Logitech',
                            'name' => 'Logitech K120 Wired Keyboard',
                            'sku' => 'PER-KB-01',
                            'hsn_code' => '8471',
                            'mrp' => 699, 'purchase_price' => 380, 'sale_price' => 549, 'stock' => 90,
                            'short_description' => 'Full-size wired keyboard for everyday office use.',
                            'description' => 'Spill-resistant, quiet keyboard with a familiar full-size layout — a dependable choice for office desks.',
                            'specs' => [
                                'Connectivity' => 'Wired USB',
                                'Layout' => 'Full-Size',
                                'Spill Resistant' => 'Yes',
                            ],
                        ],
                        [
                            'brand' => 'Zebronics',
                            'name' => 'Zebronics ZEB-K35 Wired Multimedia Keyboard',
                            'sku' => 'PER-KB-02',
                            'hsn_code' => '8471',
                            'mrp' => 449, 'purchase_price' => 250, 'sale_price' => 399, 'stock' => 100,
                            'short_description' => 'Budget multimedia keyboard with quick-access hotkeys.',
                            'description' => 'Adds dedicated multimedia hotkeys for volume, playback and browser shortcuts alongside standard typing keys.',
                            'specs' => [
                                'Connectivity' => 'Wired USB',
                                'Layout' => 'Full-Size',
                                'Hotkeys' => 'Multimedia',
                            ],
                        ],
                    ],
                    'Mouse' => [
                        [
                            'brand' => 'Logitech',
                            'name' => 'Logitech B100 Wired USB Mouse',
                            'sku' => 'PER-MS-01',
                            'hsn_code' => '8471',
                            'mrp' => 449, 'purchase_price' => 240, 'sale_price' => 349, 'stock' => 120,
                            'short_description' => 'Simple, reliable wired mouse for daily use.',
                            'description' => 'An ambidextrous optical mouse built for durability, suited to offices and shared computers.',
                            'specs' => [
                                'Connectivity' => 'Wired USB',
                                'Sensor' => 'Optical',
                                'DPI' => '1000',
                            ],
                        ],
                        [
                            'brand' => 'HP',
                            'name' => 'HP M100 Wired Mouse',
                            'sku' => 'PER-MS-02',
                            'hsn_code' => '8471',
                            'mrp' => 399, 'purchase_price' => 210, 'sale_price' => 329, 'stock' => 110,
                            'short_description' => 'Comfortable everyday wired mouse.',
                            'description' => 'A contoured design for comfortable extended use, with smooth optical tracking for general productivity.',
                            'specs' => [
                                'Connectivity' => 'Wired USB',
                                'Sensor' => 'Optical',
                                'DPI' => '1200',
                            ],
                        ],
                    ],
                    'Gamepad' => [
                        [
                            'brand' => 'Logitech',
                            'name' => 'Logitech F310 Wired Gamepad',
                            'sku' => 'PER-GP-01',
                            'hsn_code' => '8471',
                            'mrp' => 1999, 'purchase_price' => 1300, 'sale_price' => 1699, 'stock' => 25,
                            'short_description' => 'Full-featured wired gamepad for PC gaming.',
                            'description' => 'Console-style layout with dual vibration feedback and a mode switch for compatibility with a wide range of PC games.',
                            'specs' => [
                                'Connectivity' => 'Wired USB',
                                'Vibration Feedback' => 'Yes',
                                'Buttons' => '12 + D-Pad',
                            ],
                        ],
                        [
                            'brand' => 'Zebronics',
                            'name' => 'Zebronics Pro Wired Gamepad',
                            'sku' => 'PER-GP-02',
                            'hsn_code' => '8471',
                            'mrp' => 999, 'purchase_price' => 600, 'sale_price' => 849, 'stock' => 30,
                            'short_description' => 'Affordable wired gamepad for casual PC gaming.',
                            'description' => 'Compact ergonomic gamepad supporting most Windows game titles out of the box.',
                            'specs' => [
                                'Connectivity' => 'Wired USB',
                                'Vibration Feedback' => 'Yes',
                                'Buttons' => '10 + D-Pad',
                            ],
                        ],
                    ],
                    'Monitor' => [
                        [
                            'brand' => 'Dell',
                            'name' => 'Dell E2016H 19.5-inch LED Monitor',
                            'sku' => 'PER-MON-01',
                            'hsn_code' => '8528',
                            'mrp' => 6999, 'purchase_price' => 4900, 'sale_price' => 6299, 'stock' => 20,
                            'short_description' => 'Compact 19.5-inch monitor for office and home use.',
                            'description' => 'An energy-efficient LED monitor offering crisp text and images for everyday computing tasks.',
                            'specs' => [
                                'Screen Size' => '19.5-inch',
                                'Resolution' => '1600x900 HD+',
                                'Panel Type' => 'TN',
                                'Ports' => 'VGA',
                            ],
                        ],
                        [
                            'brand' => 'HP',
                            'name' => 'HP V24ie 23.8-inch FHD IPS Monitor',
                            'sku' => 'PER-MON-02',
                            'hsn_code' => '8528',
                            'mrp' => 10999, 'purchase_price' => 7900, 'sale_price' => 9499, 'stock' => 16,
                            'is_featured' => true,
                            'short_description' => 'Full-HD IPS monitor with wide viewing angles.',
                            'description' => 'An IPS panel delivers accurate colours and wide viewing angles, making it a strong all-rounder for work and casual use.',
                            'specs' => [
                                'Screen Size' => '23.8-inch',
                                'Resolution' => '1920x1080 FHD',
                                'Panel Type' => 'IPS',
                                'Ports' => 'HDMI, VGA',
                            ],
                        ],
                    ],
                    'Cabinet' => [
                        [
                            'brand' => 'Zebronics',
                            'name' => 'Zebronics Cronus Mid-Tower Cabinet',
                            'sku' => 'PER-CAB-01',
                            'hsn_code' => '8473',
                            'mrp' => 2299, 'purchase_price' => 1500, 'sale_price' => 1999, 'stock' => 22,
                            'short_description' => 'Mid-tower ATX cabinet with front USB and audio ports.',
                            'description' => 'Supports ATX/mATX motherboards with room for multiple drives and fans, plus a tempered glass side panel.',
                            'specs' => [
                                'Form Factor' => 'Mid-Tower ATX',
                                'Side Panel' => 'Tempered Glass',
                                'Front Ports' => '2 x USB, Audio',
                                'Fan Mounts' => '3',
                            ],
                        ],
                        [
                            'brand' => 'Frontech',
                            'name' => 'Frontech Elite ATX Cabinet',
                            'sku' => 'PER-CAB-02',
                            'hsn_code' => '8473',
                            'mrp' => 1799, 'purchase_price' => 1150, 'sale_price' => 1549, 'stock' => 26,
                            'short_description' => 'Budget ATX cabinet with good airflow for everyday builds.',
                            'description' => 'A no-frills ATX cabinet with mesh front panel airflow, well suited to standard office and home desktop builds.',
                            'specs' => [
                                'Form Factor' => 'Mid-Tower ATX',
                                'Side Panel' => 'Solid',
                                'Front Ports' => '2 x USB, Audio',
                                'Fan Mounts' => '2',
                            ],
                        ],
                    ],
                    'Webcam' => [
                        [
                            'brand' => 'Logitech',
                            'name' => 'Logitech C270 HD Webcam',
                            'sku' => 'PER-WEB-01',
                            'hsn_code' => '8525',
                            'mrp' => 1999, 'purchase_price' => 1300, 'sale_price' => 1699, 'stock' => 35,
                            'short_description' => 'HD 720p webcam for video calls and streaming.',
                            'description' => 'Widely used entry-level webcam offering clear 720p video with a built-in noise-reducing microphone.',
                            'specs' => [
                                'Resolution' => '720p HD',
                                'Frame Rate' => '30fps',
                                'Microphone' => 'Built-in',
                                'Mount' => 'Clip-on',
                            ],
                        ],
                    ],
                ],
            ],

            'Power & Accessories' => [
                'brands' => ['APC', 'Luminous', 'V-Guard', 'Amaron'],
                'types' => [
                    'UPS' => [
                        [
                            'brand' => 'APC',
                            'name' => 'APC BX600C-IN 600VA UPS',
                            'sku' => 'PWR-UPS-01',
                            'hsn_code' => '8504',
                            'mrp' => 3299, 'purchase_price' => 2300, 'sale_price' => 2899, 'stock' => 30,
                            'short_description' => 'Compact 600VA UPS to protect a PC and monitor from power cuts.',
                            'description' => 'Provides backup power and surge protection for a desktop PC setup, with automatic voltage regulation.',
                            'specs' => [
                                'Capacity' => '600VA',
                                'Output' => '360W',
                                'Backup Type' => 'Line Interactive',
                                'Sockets' => '4',
                            ],
                        ],
                        [
                            'brand' => 'Luminous',
                            'name' => 'Luminous Zolt 1100 UPS',
                            'sku' => 'PWR-UPS-02',
                            'hsn_code' => '8504',
                            'mrp' => 5499, 'purchase_price' => 3900, 'sale_price' => 4699, 'stock' => 18,
                            'is_featured' => true,
                            'short_description' => 'Higher-capacity UPS for PCs, routers and small networking racks.',
                            'description' => 'Extended backup time suited to running a desktop along with networking gear during longer power outages.',
                            'specs' => [
                                'Capacity' => '1100VA',
                                'Output' => '660W',
                                'Backup Type' => 'Line Interactive',
                                'Sockets' => '4',
                            ],
                        ],
                    ],
                    'Adapter' => [
                        [
                            'name' => 'Generic 12V 2A Power Adapter',
                            'sku' => 'PWR-ADP-01',
                            'hsn_code' => '8504',
                            'mrp' => 349, 'purchase_price' => 180, 'sale_price' => 279, 'stock' => 80,
                            'short_description' => 'Universal 12V 2A adapter for routers, cameras and LED strips.',
                            'description' => 'Regulated DC output suitable for powering networking equipment, CCTV cameras and other 12V devices.',
                            'specs' => [
                                'Output Voltage' => '12V DC',
                                'Current' => '2A',
                                'Connector' => '5.5x2.1mm',
                                'Input' => '100-240V AC',
                            ],
                        ],
                        [
                            'brand' => 'HP',
                            'name' => 'HP 65W Laptop Adapter',
                            'sku' => 'PWR-ADP-02',
                            'hsn_code' => '8504',
                            'mrp' => 1499, 'purchase_price' => 950, 'sale_price' => 1299, 'stock' => 40,
                            'short_description' => 'Replacement 65W charger for compatible HP laptops.',
                            'description' => 'A genuine-spec replacement adapter delivering stable charging for supported HP laptop models.',
                            'specs' => [
                                'Output' => '65W',
                                'Input' => '100-240V AC',
                                'Connector' => 'HP Slim Tip',
                                'Cable Length' => '1.8m',
                            ],
                        ],
                    ],
                    'Power Cable' => [
                        [
                            'name' => 'Generic 1.5m 3-Pin Power Cable',
                            'sku' => 'PWR-PC-01',
                            'hsn_code' => '8544',
                            'mrp' => 149, 'purchase_price' => 70, 'sale_price' => 119, 'stock' => 150,
                            'short_description' => 'Standard 3-pin power cord for PCs, monitors and printers.',
                            'description' => 'A commonly used IEC C13 power cable compatible with most desktop PCs, monitors and printers.',
                            'specs' => [
                                'Connector' => 'IEC C13',
                                'Length' => '1.5m',
                                'Pins' => '3',
                                'Rated Current' => '10A',
                            ],
                        ],
                    ],
                    'Battery' => [
                        [
                            'brand' => 'Amaron',
                            'name' => 'Amaron 12V 7Ah UPS Battery',
                            'sku' => 'PWR-BAT-01',
                            'hsn_code' => '8507',
                            'mrp' => 1299, 'purchase_price' => 850, 'sale_price' => 1099, 'stock' => 40,
                            'short_description' => 'Sealed lead-acid battery for small UPS and inverter systems.',
                            'description' => 'Maintenance-free SMF battery designed as a replacement backup cell for compact UPS units.',
                            'specs' => [
                                'Capacity' => '7Ah',
                                'Voltage' => '12V',
                                'Type' => 'Sealed Lead-Acid (SMF)',
                                'Warranty' => 'Standard Manufacturer Warranty',
                            ],
                        ],
                    ],
                ],
            ],

            'Printing' => [
                'brands' => ['HP', 'Canon', 'Epson', 'Brother'],
                'types' => [
                    'Printer' => [
                        [
                            'brand' => 'HP',
                            'name' => 'HP DeskJet 2331 All-in-One Printer',
                            'sku' => 'PRN-PRT-01',
                            'hsn_code' => '8443',
                            'mrp' => 3199, 'purchase_price' => 2300, 'sale_price' => 2899, 'stock' => 25,
                            'short_description' => 'Entry-level print, scan and copy all-in-one for home use.',
                            'description' => 'A compact inkjet all-in-one suited to occasional home printing, scanning and copying needs.',
                            'specs' => [
                                'Functions' => 'Print, Scan, Copy',
                                'Connectivity' => 'USB',
                                'Print Type' => 'Inkjet',
                            ],
                        ],
                        [
                            'brand' => 'Canon',
                            'name' => 'Canon PIXMA E477 All-in-One Printer',
                            'sku' => 'PRN-PRT-02',
                            'hsn_code' => '8443',
                            'mrp' => 4999, 'purchase_price' => 3600, 'sale_price' => 4399, 'stock' => 20,
                            'short_description' => 'Wireless all-in-one inkjet printer with duplex printing.',
                            'description' => 'Adds Wi-Fi connectivity and automatic duplex printing to everyday home and small-office print jobs.',
                            'specs' => [
                                'Functions' => 'Print, Scan, Copy',
                                'Connectivity' => 'USB, Wi-Fi',
                                'Print Type' => 'Inkjet',
                            ],
                        ],
                        [
                            'brand' => 'Epson',
                            'name' => 'Epson L3250 EcoTank Wi-Fi Printer',
                            'sku' => 'PRN-PRT-03',
                            'hsn_code' => '8443',
                            'mrp' => 18999, 'purchase_price' => 14500, 'sale_price' => 16999, 'stock' => 10,
                            'is_featured' => true,
                            'short_description' => 'Refillable ink tank printer built for high-volume, low-cost printing.',
                            'description' => 'EcoTank design replaces cartridges with refillable tanks, dramatically cutting the cost per page for heavy printing.',
                            'specs' => [
                                'Functions' => 'Print, Scan, Copy',
                                'Connectivity' => 'USB, Wi-Fi',
                                'Print Type' => 'Ink Tank',
                            ],
                        ],
                    ],
                    'Toner Cartridge' => [
                        [
                            'brand' => 'HP',
                            'name' => 'HP 12A Black Toner Cartridge',
                            'sku' => 'PRN-TNR-01',
                            'hsn_code' => '8443',
                            'mrp' => 2799, 'purchase_price' => 1900, 'sale_price' => 2499, 'stock' => 35,
                            'short_description' => 'Original black toner cartridge for compatible HP LaserJet printers.',
                            'description' => 'Genuine HP toner cartridge engineered for consistent print quality across its rated page yield.',
                            'specs' => [
                                'Color' => 'Black',
                                'Page Yield' => 'Approx. 2000 pages',
                                'Compatibility' => 'HP LaserJet 1010/1020/1022/M1005/3050',
                            ],
                        ],
                        [
                            'brand' => 'Canon',
                            'name' => 'Canon 337 Toner Cartridge',
                            'sku' => 'PRN-TNR-02',
                            'hsn_code' => '8443',
                            'mrp' => 3299, 'purchase_price' => 2300, 'sale_price' => 2899, 'stock' => 28,
                            'short_description' => 'Original toner cartridge for compatible Canon laser printers.',
                            'description' => 'A genuine replacement cartridge that maintains sharp, consistent output for supported Canon laser printer models.',
                            'specs' => [
                                'Color' => 'Black',
                                'Page Yield' => 'Approx. 2100 pages',
                                'Compatibility' => 'Canon imageCLASS MF211/MF212/MF215',
                            ],
                        ],
                    ],
                    'Pickup Roller' => [
                        [
                            'name' => 'Generic Paper Pickup Roller for HP LaserJet 1020',
                            'sku' => 'PRN-ROL-01',
                            'hsn_code' => '8443',
                            'mrp' => 249, 'purchase_price' => 120, 'sale_price' => 199, 'stock' => 60,
                            'short_description' => 'Replacement pickup roller to fix paper-feed jams.',
                            'description' => 'A direct-fit replacement part for resolving paper feed and misfeed issues on compatible HP LaserJet models.',
                            'specs' => [
                                'Compatibility' => 'HP LaserJet 1018/1020',
                                'Material' => 'Rubber',
                                'Type' => 'Paper Pickup Roller',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
