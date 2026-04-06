@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Header --}} 
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Super Admin Dashboard</h1>
        <p class="text-gray-600">⚡ God Mode Active · All organisations visible</p>
    </div>

    {{-- Key Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Active Children</h3>
            <p class="text-3xl font-bold text-indigo-600">2,847</p>
            <p class="text-green-600 text-sm">↑ +12% this week</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Organisations</h3>
            <p class="text-3xl font-bold text-indigo-600">34</p>
            <p class="text-green-600 text-sm">↑ +3 this month</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Published Stories</h3>
            <p class="text-3xl font-bold text-indigo-600">183</p>
            <p class="text-gray-600 text-sm">65 tribes covered</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium">Badges Earned</h3>
            <p class="text-3xl font-bold text-indigo-600">9,240</p>
            <p class="text-gray-600 text-sm">Last 7 days</p>
        </div>
    </div>

    {{-- Organisations Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Organisations</h2>
        </div>
        
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Organisation</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Plan</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Children</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4">Naluwooza Creative</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">Enterprise</span></td>
                    <td class="px-6 py-4">428</td>
                    <td class="px-6 py-4"><span class="text-green-600">● Active</span></td>
                    <td class="px-6 py-4"><a href="#" class="text-indigo-600">Edit</a></td>
                </tr>
                <tr>
                    <td class="px-6 py-4">Uganda Schools Pilot</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-sm">School</span></td>
                    <td class="px-6 py-4">1,203</td>
                    <td class="px-6 py-4"><span class="text-green-600">● Active</span></td>
                    <td class="px-6 py-4"><a href="#" class="text-indigo-600">Edit</a></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Module Control --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Module Control — Global Toggles</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="comics" checked class="form-checkbox">
                    <label for="comics">📖 Comics</label>
                </div>
                <p class="text-gray-600 text-sm">Story panel viewer</p>
            </div>
            
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="songs" checked class="form-checkbox">
                    <label for="songs">🎵 Songs & Audio</label>
                </div>
                <p class="text-gray-600 text-sm">Music + pronunciation</p>
            </div>
            
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="flashcards" checked class="form-checkbox">
                    <label for="flashcards">🃏 Flashcards</label>
                </div>
                <p class="text-gray-600 text-sm">Vocab + language</p>
            </div>
            
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="bundles" checked class="form-checkbox">
                    <label for="bundles">📦 Offline Bundles</label>
                </div>
                <p class="text-gray-600 text-sm">.ckb download system</p>
            </div>
            
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="theme" checked class="form-checkbox">
                    <label for="theme">🎨 Theme Engine</label>
                </div>
                <p class="text-gray-600 text-sm">Org branding override</p>
            </div>
            
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="kiosk" checked class="form-checkbox">
                    <label for="kiosk">🖥️ Kiosk Mode</label>
                </div>
                <p class="text-gray-600 text-sm">Classroom tablets</p>
            </div>
        </div>
    </div>
</div>
@endsection
