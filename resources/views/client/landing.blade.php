@extends('layouts.app')

@section('title', 'PawTulong | Client Landing')

@php
    use Illuminate\Support\Str;
    use App\Models\ChatLog;
    use Illuminate\Support\Facades\DB;

    /**
     * Variables passed:
     * $user, $mostAsked, $recentConversations, $totalChats
     */
    $layoutCss = 'landing.css';
    $page = 'home';

    // ✅ Count only frequently asked questions (asked > 1 time)
    $frequentCount = ChatLog::select('question', DB::raw('COUNT(*) as count'))
        ->whereNotNull('question')
        ->where('question', '!=', '')
        ->groupBy('question')
        ->havingRaw('COUNT(*) > 1')
        ->count();
@endphp

@section('content')
<div class="client-landing-container">

  {{-- 📊 Summary Cards --}}
  <div class="summary-cards">
    @php
      $cards = [
        ['label'=>'Most Asked Questions', 'value'=>$frequentCount, 'color'=>'#8b5c8b'],
      ];
    @endphp
    @foreach($cards as $card)
      <div class="summary-card" style="background-color: {{ $card['color'] }}20;">
        <div class="summary-card-label">{{ $card['label'] }}</div>
        <div class="summary-card-value">{{ $card['value'] }}</div>
      </div>
    @endforeach
  </div>

  {{-- 💡 Narrative Insight --}}
  <div class="narrative-insight">
    <p>
      💡 You’ve been chatting actively! Explore your top questions and revisit your last 10 conversations below.
    </p>
  </div>

  {{-- 🧭 Main Layout --}}
  <div class="main-layout">

    {{-- 💬 Frequently Asked Questions --}}
    <div class="faq-section">
      <h3>💬 Frequently Asked Questions</h3>
      <ul class="faq-list">
        @php
          // ✅ Get only questions asked more than once
          $frequentQs = ChatLog::select('question', DB::raw('COUNT(*) as count'))
              ->whereNotNull('question')
              ->where('question', '!=', '')
              ->groupBy('question')
              ->havingRaw('COUNT(*) > 1')
              ->orderByDesc('count')
              ->limit(10)
              ->pluck('question');
        @endphp

        @forelse($frequentQs as $i => $q)
          @php
            $answer = ChatLog::where('question', $q)
                ->whereNotNull('answer')
                ->where('answer', '!=', '')
                ->orderByDesc('created_at')
                ->value('answer');
          @endphp

          <li class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
              <strong>#{{ $i + 1 }}</strong> — {{ Str::limit($q, 80) }}
            </div>
            <div class="faq-answer" style="display:none;">
              {!! $answer ? e($answer) : '<em>No answer recorded.</em>' !!}
            </div>
          </li>
        @empty
          <li style="text-align:center;color:#999;">No frequently asked questions yet.</li>
        @endforelse
      </ul>
    </div>

    {{-- 📝 Recent Conversations --}}
    <div class="recent-conversations">
      <h3>📝 Recent Conversations</h3>
      <div class="conversations-grid">
        @forelse($recentConversations ?? [] as $chat)
          <a href="{{ route('chatbot.show', $chat->chat_session_id) }}" class="conversation-link">
            <div class="conversation-item">
              <div><strong>Question:</strong> {{ Str::limit($chat->question, 100) }}</div>
              <div><strong>Answer:</strong> {{ Str::limit($chat->answer ?? 'No answer', 120) }}</div>
              <div class="conversation-date">{{ $chat->created_at->diffForHumans() }}</div>
            </div>
          </a>
        @empty
          <div style="text-align:center;color:#999;">No recent conversations yet.</div>
        @endforelse
      </div>
    </div>

  </div>
</div>

{{-- 💡 JS for toggling FAQ answers --}}
<script>
function toggleFAQ(element) {
  const answer = element.nextElementSibling;
  answer.style.display = answer.style.display === "none" ? "block" : "none";
}
</script>

<hr class="divider" style="margin: 40px auto; width: 80%; border: 1px solid #d8b3d8;">

<h1 class="clinic-title" style="text-align:center; color:#5a2d5a; margin-top:40px;">
    Vet Clinic Locations in San Fernando City, La Union
</h1>

<div class="clinic-grid" style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    justify-content: center;
    padding: 30px 40px;
">
    @php
        $clinics = [
            ['name'=> 'Provincial Veterinary Office Of La Union', 'address' => 'J859+VG9, Aguila Road, San Fernando City, La Union','phone' =>'+63 72 607 0248','lat' => 16.60963680718433, 'lng' =>120.31884495417549],
            ['name' => 'Valley Vets (Animal Clinic)', 'address' => '181 Manila N Rd, San Fernando City, La Union', 'phone' => '+63 906 962 0694', 'lat' => 16.63947808101212, 'lng' =>  120.31102000059168], 
            ['name' => 'New Creation Animal Clinic La Union', 'address' => 'Salanga Building, Mabini St, San Fernando City, 2500 La Union', 'phone' => '+63 966 492 8022', 'lat' => 16.609868934070676,  'lng' => 120.31058488758917],
            ['name' => 'Primecare Animal Recovery Clinic', 'address' => 'J839+9X2, San Fernando By-Pass Rd, San Fernando City, La Union', 'phone' => '+63 939 835 6111', 'lat' => 16.603266, 'lng' =>  120.319919],
            ['name' => 'Clinicovet Animal House', 'address' => 'J8J9+329, Manila N Rd, San Fernando City, La Union', 'phone' => '+63 907 941 1645', 'lat' => 16.630623, 'lng' =>  120.317771],
            ['name' => 'Animasolution Inc.- Vetspets Animaland', 'address' => 'Abubo Bldg, Corner Guerrero Rd, San Fernando City, La Union', 'phone' => '+63 72 619 4178', 'lat' => 16.612285,  'lng' => 120.316702],
            ['name' => 'Gold Cape Veterinary Clinic & Supply', 'address' => 'J8HC+5WH, Biday Road, San Fernando City, La Union', 'phone' => '', 'lat' => 16.628335, 'lng' => 120.322377],
            ['name'=> 'Songcuan Trading Veterinary Medicine, Equipments, Supplies And Servicesn', 'address' => 'J898+89W, P. Burgos St, San Fernando City, La Union','phone' =>'+63 72 607 5304','lat' => 16.61836069729509, 'lng' => 120.3159501807594],
        ];
    @endphp

    @foreach($clinics as $clinic)
        <div class="clinic-card" style="
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 15px;
            transition: transform 0.2s ease;
            text-align: center;
        " onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
            <div id="map-{{ $loop->index }}" class="clinic-map" style="
                width: 100%;
                height: 200px;
                border-radius: 8px;
                margin-bottom: 10px;
            "></div>
            <div class="clinic-info">
                <h4 style="color:#5a2d5a; margin-bottom:8px;">{{ $clinic['name'] }}</h4>
                <p style="font-size:14px; color:#555;"><strong>Address:</strong> {{ $clinic['address'] }}</p>
                @if($clinic['phone'])
                    <p style="font-size:14px; color:#555;"><strong>Phone:</strong> {{ $clinic['phone'] }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const clinics = @json($clinics);
    clinics.forEach((clinic, index) => {
        const map = L.map('map-' + index, { zoomControl: false, attributionControl: false })
            .setView([clinic.lat, clinic.lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([clinic.lat, clinic.lng]).addTo(map)
            .bindPopup(`<b>${clinic.name}</b><br>${clinic.address}`);
    });
});
</script>

<hr class="divider" style="margin: 60px auto; width: 80%; border: 1px solid #d8b3d8;">

<h1 style="text-align:center; color:#5a2d5a; margin-top:40px;">
    AAFCO Standards & Toxic Foods for Pets
</h1>

<div style="text-align:center; margin:20px auto;">
    <p style="color:#555;">Guidelines from the Association of American Feed Control Officials (AAFCO) and common toxic foods for dogs and cats.</p>
</div>

<div class="pet-card" style="background:#fff7fc; border-radius:12px; padding:25px; box-shadow:0 5px 15px rgba(0,0,0,0.1); margin:20px;">


    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:30px; margin-top:20px;">
        <div>
            <h2 style="color:#8b5c8b;">Canine</h2>
            <ul style="font-size:14px; color:#333; line-height:1.6; margin-left:20px;">
                <li><strong>Protein:</strong> ≥18%</li>
                <li><strong>Fat:</strong> ≥5.5%</li>
                <li><strong>Essential Nutrients:</strong> Amino acids, Omega-3 & 6, Vitamins A/D/E/K/B, Minerals (Ca, P, Zn, Fe, Cu)</li>
                <li><em>Label:</em> “Meets AAFCO Dog Food Nutrient Profiles”</li>
            </ul>
        </div>

        <div>
            <h2 style="color:#8b5c8b;">Feline</h2>
            <ul style="font-size:14px; color:#333; line-height:1.6; margin-left:20px;">
                <li><strong>Protein:</strong> ≥26%</li>
                <li><strong>Fat:</strong> ≥9%</li>
                <li><strong>Key Nutrients:</strong> Taurine, Arachidonic acid, Vitamin A, Niacin, Thiamine</li>
                <li><em>Label:</em> “Meets AAFCO Cat Food Nutrient Profiles”</li>
            </ul>
        </div>
    </div>
</div>

<hr style="margin:40px auto; width:70%; border:0; border-top:1px  #d8b3d8;">
<h1 style="text-align:center; color:#5a2d5a; margin-top:50px;">
    Common Poisons & Toxic Foods for Pets
</h1>
<div class="pet-info-grid" style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
    padding: 10px 20px;
">
    {{-- 🐶 Dogs --}}
    <div class="pet-card" style="
        background:#fff7fc;
        border-radius:10px;
        padding:15px;
        box-shadow:0 3px 8px rgba(0,0,0,0.1);
    ">
        <h3 style="color:#8b5c8b; font-size:16px; margin-bottom:10px;">Toxic Foods for Canine</h3>
        <ul style="font-size:13px; color:#333; line-height:1.5; margin-left:15px;">
            <li>🍫 <strong>Chocolate</strong> — vomiting, seizures, heart issues</li>
            <li>🧅 <strong>Onions / Garlic</strong> — anemia, weakness</li>
            <li>🍇 <strong>Grapes / Raisins</strong> — kidney failure</li>
            <li>☕ <strong>Caffeine / Coffee</strong> — hyperactivity, heart rhythm issues</li>
            <li>🍬 <strong>Xylitol (sweetener)</strong> — low blood sugar, liver failure</li>
            <li>🍷 <strong>Alcohol</strong> — vomiting, coma</li>
            <li>🥑 <strong>Avocado</strong> — vomiting, heart congestion</li>
            <li>🍖 <strong>Cooked bones</strong> — splintering, internal injury</li>
        </ul>
    </div>

    {{-- 🐱 Cats --}}
    <div class="pet-card" style="
        background:#fff7fc;
        border-radius:10px;
        padding:15px;
        box-shadow:0 3px 8px rgba(0,0,0,0.1);
    ">
        <h3 style="color:#8b5c8b; font-size:16px; margin-bottom:10px;">Toxic Foods for Feline</h3>
        <ul style="font-size:13px; color:#333; line-height:1.5; margin-left:15px;">
            <li>🍫 <strong>Chocolate</strong> — vomiting, seizures</li>
            <li>🧅 <strong>Onions / Garlic</strong> — red blood cell damage</li>
            <li>☕ <strong>Caffeine / Coffee</strong> — restlessness, tremors</li>
            <li>🍷 <strong>Alcohol</strong> — depression, coma</li>
            <li>🐟 <strong>Raw fish</strong> — parasites, thiamine deficiency</li>
            <li>🌿 <strong>Lilies</strong> — kidney failure</li>
            <li>🍖 <strong>Cooked bones</strong> — choking, intestinal injury</li>
            <li>🥑 <strong>Avocado</strong> — mild stomach upset</li>
        </ul>
    </div>
</div>



@endsection
