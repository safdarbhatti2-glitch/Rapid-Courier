<!-- Hero Section -->
<section class="rc-hero" id="home">

  <!-- BACKGROUND SLIDER -->
  <div class="hero-bg" id="heroBackground"></div>
  <div class="hero-overlay"></div>
  <div class="hero-glow"></div>

  <div class="hero-container">
    <div class="hero-copy">

      <div class="eyebrow">UAE • GCC • Worldwide</div>

      <h1 class="hero-title">
        Premium courier<br>
        &amp; <span class="accent">logistics.</span>
      </h1>

      <p class="hero-description">
        Move parcels, documents and commercial cargo with a faster,
        smarter logistics partner built for the UAE and the Gulf.
      </p>

      <div class="hero-actions">
        <a class="btn btn-primary" href="<?= \App\Core\View::url('/quote') ?>">Get an Instant Quote &rarr;</a>
        <a class="btn btn-secondary" href="<?= \App\Core\View::url('/book') ?>">Book a Shipment</a>
      </div>

      <div class="hero-points">
        <span><strong>Same-Day</strong> UAE delivery</span>
        <span><strong>GCC</strong> road freight</span>
        <span><strong>24/7</strong> tracking visibility</span>
      </div>

      <form class="tracking-card" action="<?= \App\Core\View::url('/track') ?>" method="GET">
        <label class="tracking-label" for="trackingInput">Track your shipment</label>
        <div class="tracking-row">
          <input
            id="trackingInput"
            name="number"
            required
            class="tracking-input"
            placeholder="Enter AWB / tracking number (e.g. RC98412503)"
          />
          <button class="tracking-btn" type="submit">Track</button>
        </div>
      </form>

    </div>
  </div>

  <!-- INDEPENDENT VEHICLE ANIMATION LAYER -->
  <div class="vehicle-track" aria-hidden="true">
    <div class="road-glow"></div>

    <div class="vehicle" id="vehicle">
      <div class="van">
        <div class="van-logo">RC COURIER</div>
        <div class="cab">
          <div class="window"></div>
          <div class="headlight"></div>
        </div>
        <div class="wheel one"></div>
        <div class="wheel two"></div>
      </div>
    </div>
  </div>

  <!-- SLIDER CONTROLS -->
  <div class="slider-controls" aria-label="Hero background controls">
    <button class="slider-toggle" id="playPause" type="button" title="Pause slider">Ⅱ</button>
    <div id="sliderDots"></div>
  </div>

</section>

<script>
/*
=========================================================
SMART HERO CONFIGURATION
=========================================================

1) SINGLE IMAGE:
   mode: "single"
   backgrounds: ["your-image.jpg"]

2) 2–3 IMAGE SLIDER:
   mode: "slider"
   backgrounds: [
      "hero-dubai.jpg",
      "hero-cargo.jpg",
      "hero-delivery.jpg"
   ]
=========================================================
*/
const HERO_CONFIG = {
  mode: "slider",

  backgrounds: [
    "https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=2200&q=88",
    "https://images.unsplash.com/photo-1586528116493-da8b6f2b0a8b?auto=format&fit=crop&w=2200&q=88",
    "https://images.unsplash.com/photo-1494412651409-8963ce7935a7?auto=format&fit=crop&w=2200&q=88"
  ],

  interval: 6000,
  vehicleSpeed: 13
};

const bg = document.getElementById("heroBackground");
const dots = document.getElementById("sliderDots");
const badge = document.getElementById("modeBadge");
const playPause = document.getElementById("playPause");
const vehicle = document.getElementById("vehicle");

let current = 0;
let timer = null;
let paused = false;

function buildBackgrounds(){
  if (!bg || !dots || !playPause) return;
  bg.innerHTML = "";
  dots.innerHTML = "";

  const images = HERO_CONFIG.mode === "single"
    ? [HERO_CONFIG.backgrounds[0]]
    : HERO_CONFIG.backgrounds.slice(0,3);

  images.forEach((src,index)=>{
    const slide = document.createElement("div");
    slide.className = "hero-slide" + (index === 0 ? " active" : "");
    slide.style.backgroundImage = `url("${src}")`;
    bg.appendChild(slide);

    if(images.length > 1){
      const dot = document.createElement("button");
      dot.className = "slider-dot" + (index === 0 ? " active" : "");
      dot.setAttribute("aria-label",`Show background ${index+1}`);
      dot.onclick = ()=>goToSlide(index);
      dots.appendChild(dot);
    }
  });

  if (badge) {
    badge.textContent = images.length === 1
      ? "single background"
      : `${images.length} background slides`;
  }

  if(images.length === 1){
    playPause.style.display = "none";
  }else{
    playPause.style.display = "block";
  }
}

function goToSlide(index){
  const slides = [...document.querySelectorAll(".hero-slide")];
  const indicators = [...document.querySelectorAll(".slider-dot")];

  if(!slides.length) return;

  current = (index + slides.length) % slides.length;

  slides.forEach((s,i)=>s.classList.toggle("active",i===current));
  indicators.forEach((d,i)=>d.classList.toggle("active",i===current));

  restartTimer();
}

function nextSlide(){
  const count = document.querySelectorAll(".hero-slide").length;
  if(count > 1) goToSlide(current + 1);
}

function restartTimer(){
  clearInterval(timer);
  if(!paused && HERO_CONFIG.mode === "slider"){
    timer = setInterval(nextSlide,HERO_CONFIG.interval);
  }
}

if (playPause) {
  playPause.addEventListener("click",()=>{
    paused = !paused;
    playPause.textContent = paused ? "▶" : "Ⅱ";
    playPause.title = paused ? "Play slider" : "Pause slider";
    restartTimer();
  });
}

/* Initialize */
buildBackgrounds();
restartTimer();

/* Pause slider when the browser tab is hidden */
document.addEventListener("visibilitychange",()=>{
  if(document.hidden){
    clearInterval(timer);
  }else{
    restartTimer();
  }
});
</script>

<!-- Quick Actions Grid -->
<div class="quick-wrap">
    <div class="container quick-grid">
        <article class="quick-card">
            <div class="quick-icon">⌁</div>
            <h3>Instant Quote</h3>
            <p>Get an estimated delivery price in seconds.</p>
            <a href="<?= \App\Core\View::url('/quote') ?>">Calculate rate &rarr;</a>
        </article>
        <article class="quick-card">
            <div class="quick-icon">▣</div>
            <h3>Book Shipment</h3>
            <p>Schedule a doorstep pickup without the calls.</p>
            <a href="<?= \App\Core\View::url('/book') ?>">Start booking &rarr;</a>
        </article>
        <article class="quick-card">
            <div class="quick-icon">▤</div>
            <h3>Business Solutions</h3>
            <p>Dedicated logistics for growing companies.</p>
            <a href="<?= \App\Core\View::url('/contact') ?>">Talk to sales &rarr;</a>
        </article>
        <article class="quick-card">
            <div class="quick-icon">⌘</div>
            <h3>API Integration</h3>
            <p>Connect your store or platform to RC Courier.</p>
            <a href="<?= \App\Core\View::url('/contact') ?>">Explore integration &rarr;</a>
        </article>
    </div>
</div>

<!-- Services Section -->
<section class="services" id="services">
    <div class="container">
        <div class="section-head">
            <div class="eyebrow">Our Services</div>
            <h2>One logistics partner. Every delivery.</h2>
            <p>From urgent documents to commercial cargo, RC Courier gives UAE and GCC businesses a single premium logistics experience.</p>
        </div>

        <div class="service-grid">
            <article class="service-card">
                <div>
                    <div class="service-icon">◈</div>
                    <span class="tag">Express Same-Day</span>
                    <h3>Same-Day Delivery</h3>
                    <p>Fast intra-UAE delivery for urgent parcels and time-critical documents within 6 hours.</p>
                </div>
                <a href="<?= \App\Core\View::url('/book') ?>" class="learn">Book Service <span>&rarr;</span></a>
            </article>

            <article class="service-card">
                <div>
                    <div class="service-icon">◉</div>
                    <span class="tag">Next-Day</span>
                    <h3>Next-Day Delivery</h3>
                    <p>Reliable next-business-day delivery across all seven Emirates guaranteed.</p>
                </div>
                <a href="<?= \App\Core\View::url('/book') ?>" class="learn">Book Service <span>&rarr;</span></a>
            </article>

            <article class="service-card">
                <div>
                    <div class="service-icon">◇</div>
                    <span class="tag">GCC Overland</span>
                    <h3>GCC Road Freight</h3>
                    <p>Door-to-door road logistics connecting UAE with Saudi Arabia, Oman, Kuwait, Bahrain, and Qatar.</p>
                </div>
                <a href="<?= \App\Core\View::url('/quote') ?>" class="learn">Get Freight Rate <span>&rarr;</span></a>
            </article>

            <article class="service-card">
                <div>
                    <div class="service-icon">✈</div>
                    <span class="tag">International</span>
                    <h3>International Air Freight</h3>
                    <p>Global parcel and cargo solutions for 220+ worldwide destinations.</p>
                </div>
                <a href="<?= \App\Core\View::url('/quote') ?>" class="learn">Explore Air Cargo <span>&rarr;</span></a>
            </article>

            <article class="service-card">
                <div>
                    <div class="service-icon">≈</div>
                    <span class="tag">Sea Freight</span>
                    <h3>Sea Freight</h3>
                    <p>Cost-effective ocean freight for commercial containerized shipments.</p>
                </div>
                <a href="<?= \App\Core\View::url('/contact') ?>" class="learn">Contact Cargo Desk <span>&rarr;</span></a>
            </article>

            <article class="service-card">
                <div>
                    <div class="service-icon">▥</div>
                    <span class="tag">Warehousing</span>
                    <h3>Warehousing & Storage</h3>
                    <p>Secure inventory storage and fulfillment support at Dubai Logistics City Central Hub.</p>
                </div>
                <a href="<?= \App\Core\View::url('/contact') ?>" class="learn">Inquire Fulfillment <span>&rarr;</span></a>
            </article>

            <article class="service-card">
                <div>
                    <div class="service-icon">✓</div>
                    <span class="tag">Customs</span>
                    <h3>Customs Clearance</h3>
                    <p>Guidance and documentation support for smooth regional GCC shipments.</p>
                </div>
                <a href="<?= \App\Core\View::url('/contact') ?>" class="learn">Talk to Clearance Desk <span>&rarr;</span></a>
            </article>

            <article class="service-card">
                <div>
                    <div class="service-icon">□</div>
                    <span class="tag">E-Commerce</span>
                    <h3>E-Commerce Logistics</h3>
                    <p>Pickup, fulfillment, delivery and COD returns designed for online merchants.</p>
                </div>
                <a href="<?= \App\Core\View::url('/contact') ?>" class="learn">Merchant Solutions <span>&rarr;</span></a>
            </article>
        </div>
    </div>
</section>

<!-- Coverage Section -->
<section class="coverage" id="coverage">
    <div class="container">
        <div class="section-head">
            <div class="eyebrow">Our Coverage</div>
            <h2>UAE today. Gulf tomorrow.</h2>
            <p>Built for dependable movement across all seven Emirates and the wider GCC.</p>
        </div>

        <div class="country-grid">
            <div class="country"><div class="country-icon">⌂</div><h3>UAE</h3><p>All 7 Emirates</p></div>
            <div class="country"><div class="country-icon">▥</div><h3>Saudi Arabia</h3><p>All Regions</p></div>
            <div class="country"><div class="country-icon">◉</div><h3>Qatar</h3><p>All Regions</p></div>
            <div class="country"><div class="country-icon">◇</div><h3>Kuwait</h3><p>All Regions</p></div>
            <div class="country"><div class="country-icon">✦</div><h3>Bahrain</h3><p>All Regions</p></div>
            <div class="country"><div class="country-icon">◈</div><h3>Oman</h3><p>All Regions</p></div>
        </div>

        <div class="metrics">
            <div class="metric"><strong>99.8%</strong><span>On-time delivery target</span></div>
            <div class="metric"><strong>24/7</strong><span>Shipment visibility</span></div>
            <div class="metric"><strong>7</strong><span>UAE Emirates covered</span></div>
            <div class="metric"><strong>220+</strong><span>Global destinations</span></div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="process" id="about">
    <div class="container">
        <div class="section-head">
            <div class="eyebrow">How It Works</div>
            <h2>Four steps. Zero confusion.</h2>
            <p>A premium shipping experience from the first click to the final delivery.</p>
        </div>

        <div class="steps">
            <div class="step"><div class="step-no">01</div><h3>Book</h3><p>Enter pickup, delivery and parcel details.</p></div>
            <div class="step"><div class="step-no">02</div><h3>We Pick Up</h3><p>Our courier collects your shipment from your door.</p></div>
            <div class="step"><div class="step-no">03</div><h3>In Transit</h3><p>Follow every milestone with live status updates.</p></div>
            <div class="step"><div class="step-no">04</div><h3>Delivered</h3><p>Secure handover with proof of delivery.</p></div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials">
    <div class="container">
        <div class="section-head">
            <div class="eyebrow">Customer Experience</div>
            <h2>Built around reliability.</h2>
            <p>Trusted by UAE commercial enterprises and individual senders alike.</p>
        </div>

        <div class="testimonial-grid">
            <article class="quote">
                <div class="stars">★★★★★</div>
                <p>“RC Courier gives our UAE operations the visibility and response time we need to keep customers happy.”</p>
                <div class="person">
                    <div class="avatar">AM</div>
                    <div><strong>Ahmed M.</strong><small>Operations Manager · Dubai</small></div>
                </div>
            </article>
            <article class="quote">
                <div class="stars">★★★★★</div>
                <p>“The GCC coverage and professional handling make RC Courier a strong partner for our regional shipments.”</p>
                <div class="person">
                    <div class="avatar">FA</div>
                    <div><strong>Fatima A.</strong><small>Commercial Director · Riyadh</small></div>
                </div>
            </article>
            <article class="quote">
                <div class="stars">★★★★★</div>
                <p>“Fast pickup, clear tracking and responsive support. Exactly what an e-commerce business needs.”</p>
                <div class="person">
                    <div class="avatar">KS</div>
                    <div><strong>Khalid S.</strong><small>E-Commerce Manager · Abu Dhabi</small></div>
                </div>
            </article>
        </div>

        <div style="margin-top:55px" id="contact">
            <div class="cta">
                <div>
                    <h2>Ready to move with confidence?</h2>
                    <p>Get your RC Courier shipment moving across the UAE and Gulf.</p>
                </div>
                <div style="display:flex;gap:9px;flex-wrap:wrap">
                    <a href="<?= \App\Core\View::url('/quote') ?>" class="btn" style="background:#07101b;color:#fff">Get a Quote &rarr;</a>
                    <a href="<?= \App\Core\View::url('/book') ?>" class="btn" style="background:#fff;color:#07101b">Book Shipment &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>
