<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatkulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['matkul_id' => 1, 'matkul_nama' => 'Algoritma Evolusioner'],
            ['matkul_id' => 2, 'matkul_nama' => 'Analisis Data'],
            ['matkul_id' => 3, 'matkul_nama' => 'Aplikasi Permainan'],
            ['matkul_id' => 4, 'matkul_nama' => 'Artificial Intelligence'],
            ['matkul_id' => 5, 'matkul_nama' => 'Attention Based RNN'],
            ['matkul_id' => 6, 'matkul_nama' => 'Augmented Reality (AR)'],
            ['matkul_id' => 7, 'matkul_nama' => 'Big Data'],
            ['matkul_id' => 8, 'matkul_nama' => 'Clustering'],
            ['matkul_id' => 9, 'matkul_nama' => 'Cognitive Artificial Intelligence'],
            ['matkul_id' => 10, 'matkul_nama' => 'Data Analysis'],
            ['matkul_id' => 11, 'matkul_nama' => 'Data Mining'],
            ['matkul_id' => 12, 'matkul_nama' => 'Data Science'],
            ['matkul_id' => 13, 'matkul_nama' => 'Data Warehouse'],
            ['matkul_id' => 14, 'matkul_nama' => 'Decision Support System'],
            ['matkul_id' => 15, 'matkul_nama' => 'Deep Learning'],
            ['matkul_id' => 16, 'matkul_nama' => 'Defense Technology'],
            ['matkul_id' => 17, 'matkul_nama' => 'Enterprise Resource Planning (ERP)'],
            ['matkul_id' => 18, 'matkul_nama' => 'Fake News Detection'],
            ['matkul_id' => 19, 'matkul_nama' => 'Game'],
            ['matkul_id' => 20, 'matkul_nama' => 'Geographic Information System (GIS)'],
            ['matkul_id' => 21, 'matkul_nama' => 'Human Computer Interaction (HCI)'],
            ['matkul_id' => 22, 'matkul_nama' => 'Image Processing'],
            ['matkul_id' => 23, 'matkul_nama' => 'Information Fusion'],
            ['matkul_id' => 24, 'matkul_nama' => 'Information Retrieval'],
            ['matkul_id' => 25, 'matkul_nama' => 'Infrastruktur Server dan Jaringan'],
            ['matkul_id' => 26, 'matkul_nama' => 'Internet of Things (IOT)'],
            ['matkul_id' => 27, 'matkul_nama' => 'Keamanan Informasi Jaringan'],
            ['matkul_id' => 28, 'matkul_nama' => 'Keamanan Jaringan'],
            ['matkul_id' => 29, 'matkul_nama' => 'Kecerdasan Buatan'],
            ['matkul_id' => 30, 'matkul_nama' => 'Kecerdasan Komputasional'],
            ['matkul_id' => 31, 'matkul_nama' => 'Klasifikasi'],
            ['matkul_id' => 32, 'matkul_nama' => 'Komputasi Awan'],
            ['matkul_id' => 33, 'matkul_nama' => 'Komputasi Berbasis Jaringan'],
            ['matkul_id' => 34, 'matkul_nama' => 'Large Language Model (LLM)'],
            ['matkul_id' => 35, 'matkul_nama' => 'Learning Engineering'],
            ['matkul_id' => 36, 'matkul_nama' => 'Learning Engineering Technology (LET)'],
            ['matkul_id' => 37, 'matkul_nama' => 'Machine Learning'],
            ['matkul_id' => 38, 'matkul_nama' => 'Mobile Application'],
            ['matkul_id' => 39, 'matkul_nama' => 'Multimedia'],
            ['matkul_id' => 40, 'matkul_nama' => 'Natural Language Processing (NLP)'],
            ['matkul_id' => 41, 'matkul_nama' => 'Optical Character Recognition (OCR)'],
            ['matkul_id' => 42, 'matkul_nama' => 'Optimasi Basis Data'],
            ['matkul_id' => 43, 'matkul_nama' => 'Pattern Recognition'],
            ['matkul_id' => 44, 'matkul_nama' => 'Pembelajaran Mesin'],
            ['matkul_id' => 45, 'matkul_nama' => 'Pengembangan Teknologi Mobile'],
            ['matkul_id' => 46, 'matkul_nama' => 'Pengolahan Citra'],
            ['matkul_id' => 47, 'matkul_nama' => 'Quality Assurance'],
            ['matkul_id' => 48, 'matkul_nama' => 'Recommender System'],
            ['matkul_id' => 49, 'matkul_nama' => 'Reinforcement Learning'],
            ['matkul_id' => 50, 'matkul_nama' => 'Rekayasa Perangkat Lunak'],
            ['matkul_id' => 51, 'matkul_nama' => 'Semantic Analysis'],
            ['matkul_id' => 52, 'matkul_nama' => 'Sentiment Analysis'],
            ['matkul_id' => 53, 'matkul_nama' => 'Sintactic Analysis'],
            ['matkul_id' => 54, 'matkul_nama' => 'Sistem Cerdas'],
            ['matkul_id' => 55, 'matkul_nama' => 'Sistem Informasi'],
            ['matkul_id' => 56, 'matkul_nama' => 'Sistem Pendukung Keputusan (SPK)'],
            ['matkul_id' => 57, 'matkul_nama' => 'Sistem Prediksi'],
            ['matkul_id' => 58, 'matkul_nama' => 'Sistem Rekomendasi'],
            ['matkul_id' => 59, 'matkul_nama' => 'Software Engineering'],
            ['matkul_id' => 60, 'matkul_nama' => 'Surveillance Information Systems'],
            ['matkul_id' => 61, 'matkul_nama' => 'Tata kelola Teknologi Informasi'],
            ['matkul_id' => 62, 'matkul_nama' => 'Technology Enhanced Learning'],
            ['matkul_id' => 63, 'matkul_nama' => 'Teknologi Jaringan'],
            ['matkul_id' => 64, 'matkul_nama' => 'Teknologi Media'],
            ['matkul_id' => 65, 'matkul_nama' => 'Text Mining'],
            ['matkul_id' => 66, 'matkul_nama' => 'Text Processing'],
            ['matkul_id' => 67, 'matkul_nama' => 'Text Summarization'],
            ['matkul_id' => 68, 'matkul_nama' => 'Topic Modelling'],
            ['matkul_id' => 69, 'matkul_nama' => 'UI/UX'],
            ['matkul_id' => 70, 'matkul_nama' => 'UMKM'],
            ['matkul_id' => 71, 'matkul_nama' => 'Virtual Reality (VR)'],
            ['matkul_id' => 72, 'matkul_nama' => 'Visualisasi'],
            ['matkul_id' => 73, 'matkul_nama' => 'Visualisasi Data'],
            ['matkul_id' => 74, 'matkul_nama' => 'Wireless Technology'],
            ['matkul_id' => 75, 'matkul_nama' => 'Biometrics']
        ];

        DB::table('m_matkul')->insert($data);
    }
}
