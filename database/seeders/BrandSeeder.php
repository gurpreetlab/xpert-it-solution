<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            // Networking
            [
                'name' => 'TP-Link',
                'description' => 'Global networking brand offering routers, switches, Wi-Fi extenders, access points, and network adapters.',
            ],
            [
                'name' => 'D-Link',
                'description' => 'Manufacturer of networking equipment including routers, switches, access points, and structured networking products.',
            ],
            [
                'name' => 'Tenda',
                'description' => 'Affordable networking brand specializing in routers, mesh Wi-Fi systems, switches, and wireless adapters.',
            ],
            [
                'name' => 'Mercusys',
                'description' => 'Budget-friendly networking brand offering routers, extenders, mesh systems, and switches.',
            ],
            [
                'name' => 'Netgear',
                'description' => 'Premium networking brand providing routers, switches, NAS devices, and business networking solutions.',
            ],
            [
                'name' => 'Cisco',
                'description' => 'Enterprise networking company known for routers, switches, wireless infrastructure, and cybersecurity solutions.',
            ],
            [
                'name' => 'Ubiquiti',
                'description' => 'Professional networking brand offering UniFi access points, switches, routers, and wireless networking products.',
            ],

            // CCTV
            [
                'name' => 'CP PLUS',
                'description' => 'Indian security brand offering CCTV cameras, DVRs, NVRs, biometric devices, and surveillance solutions.',
            ],
            [
                'name' => 'Hikvision',
                'description' => 'Global provider of CCTV cameras, IP cameras, DVRs, NVRs, and AI-powered security systems.',
            ],
            [
                'name' => 'Dahua',
                'description' => 'Manufacturer of video surveillance products, smart security systems, and access control solutions.',
            ],
            [
                'name' => 'Uniview',
                'description' => 'Surveillance brand specializing in IP cameras, NVRs, and intelligent video security solutions.',
            ],
            [
                'name' => 'Prama Hikvision',
                'description' => 'Indian security solutions provider offering CCTV systems and surveillance equipment.',
            ],
            [
                'name' => 'Secureye',
                'description' => 'Indian manufacturer of CCTV cameras, DVRs, biometric devices, and security products.',
            ],

            // Storage
            [
                'name' => 'Seagate',
                'description' => 'Manufacturer of internal hard drives, surveillance HDDs, and enterprise storage solutions.',
            ],
            [
                'name' => 'Western Digital',
                'description' => 'Storage brand offering HDDs, SSDs, memory cards, and portable storage devices.',
            ],
            [
                'name' => 'Kingston',
                'description' => 'Leading manufacturer of SSDs, RAM modules, USB drives, and memory cards.',
            ],
            [
                'name' => 'SanDisk',
                'description' => 'Flash storage brand specializing in memory cards, USB drives, and SSDs.',
            ],
            [
                'name' => 'Samsung',
                'description' => 'Electronics company producing high-performance SSDs, memory cards, and storage products.',
            ],
            [
                'name' => 'Crucial',
                'description' => 'Brand known for SSDs and memory upgrades for desktops and laptops.',
            ],
            [
                'name' => 'ADATA',
                'description' => 'Manufacturer of SSDs, RAM, USB drives, and flash memory products.',
            ],

            // Computer Peripherals
            [
                'name' => 'Logitech',
                'description' => 'Manufacturer of keyboards, mice, webcams, speakers, and gaming peripherals.',
            ],
            [
                'name' => 'HP',
                'description' => 'Technology company offering keyboards, mice, monitors, printers, and computer accessories.',
            ],
            [
                'name' => 'Dell',
                'description' => 'Computer manufacturer producing monitors, keyboards, mice, and accessories.',
            ],
            [
                'name' => 'Zebronics',
                'description' => 'Indian electronics brand offering keyboards, mice, speakers, monitors, and PC accessories.',
            ],
            [
                'name' => 'Ant Esports',
                'description' => 'Gaming brand manufacturing cabinets, keyboards, mice, cooling systems, and power supplies.',
            ],
            [
                'name' => 'Redragon',
                'description' => 'Gaming peripherals brand known for mechanical keyboards, gaming mice, and headsets.',
            ],
            [
                'name' => 'Fantech',
                'description' => 'Gaming accessories brand offering keyboards, mice, headsets, and controllers.',
            ],
            [
                'name' => 'Frontech',
                'description' => 'Indian brand producing affordable computer peripherals and accessories.',
            ],
            [
                'name' => 'Lapcare',
                'description' => 'Computer accessories brand offering keyboards, mice, webcams, laptop accessories, and networking products.',
            ],
            [
                'name' => 'Enter',
                'description' => 'Indian electronics brand providing keyboards, mice, webcams, storage devices, and accessories.',
            ],

            // Power
            [
                'name' => 'APC',
                'description' => 'Global brand manufacturing UPS systems, surge protectors, and power management solutions.',
            ],
            [
                'name' => 'Microtek',
                'description' => 'Indian manufacturer of UPS systems, inverters, voltage stabilizers, and power backup products.',
            ],
            [
                'name' => 'Numeric',
                'description' => 'Brand specializing in UPS systems, power backup solutions, and voltage protection devices.',
            ],
            [
                'name' => 'Amaron',
                'description' => 'Manufacturer of batteries and power backup solutions for various applications.',
            ],

            // Printing
            [
                'name' => 'Canon',
                'description' => 'Manufacturer of inkjet and laser printers, scanners, and printing accessories.',
            ],
            [
                'name' => 'Epson',
                'description' => 'Manufacturer of inkjet printers, EcoTank printers, scanners, and printing consumables.',
            ],
            [
                'name' => 'Brother',
                'description' => 'Office equipment brand producing printers, scanners, and multifunction devices.',
            ],
            [
                'name' => 'Pantum',
                'description' => 'Printer brand specializing in affordable laser printers and toner cartridges.',
            ],
            [
                'name' => 'Ricoh',
                'description' => 'Manufacturer of printers, copiers, multifunction devices, and office printing solutions.',
            ],
            [
                'name' => 'Xerox',
                'description' => 'Global printing technology company offering printers, multifunction devices, and document management solutions.',
            ],
        ];

        DB::transaction(function () use ($brands) {
            foreach ($brands as $brand) {
                Brand::firstOrCreate(
                    ['name' => $brand['name']],
                    ['description' => $brand['description']]
                );
            }
        }, attempts: 5);
    }
}
