(() => {
  'use strict';
  const $  = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => [...c.querySelectorAll(s)];
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const finePointer = matchMedia('(hover:hover) and (pointer:fine)').matches;
  const small = matchMedia('(max-width: 820px)').matches;

  /* Loader + entrance */
  const start = () => {
    $('#loader')?.classList.add('is-done');
    document.body.classList.add('is-ready');
    document.documentElement.classList.add('is-ready');
  };
  window.addEventListener('load', () => setTimeout(start, reduce ? 0 : 500));
  setTimeout(start, 3000); // safety net

  /* Scroll reveal (IntersectionObserver) */
  const io = new IntersectionObserver((entries) => {
    entries.forEach(en => {
      if (!en.isIntersecting) return;
      const siblings = $$('.reveal', en.target.parentElement);
      en.target.style.transitionDelay = reduce ? '0s' : Math.min(siblings.indexOf(en.target), 6) * 70 + 'ms';
      en.target.classList.add('is-in');
      io.unobserve(en.target);
    });
  }, { threshold: .12, rootMargin: '0px 0px -6% 0px' });
  $$('.reveal').forEach(el => io.observe(el));

  /* Rotating role (kinetic typing) */
  const role = $('#role');
  if (role) {
    let roles = [];
    try { roles = JSON.parse(role.dataset.roles); } catch (_) {}
    if (roles.length > 1 && !reduce) {
      let r = 0, c = roles[0].length, del = true;
      const tick = () => {
        const word = roles[r];
        c += del ? -1 : 1;
        role.textContent = word.slice(0, c);
        let wait = del ? 35 : 70;
        if (!del && c === word.length) { del = true; wait = 1600; }
        else if (del && c === 0) { del = false; r = (r + 1) % roles.length; wait = 300; }
        setTimeout(tick, wait);
      };
      setTimeout(tick, 1800);
    }
  }

  /* Nav: scroll spy, mobile toggle */
  const links = $$('[data-spy]');
  const spy = new IntersectionObserver((es) => {
    es.forEach(e => { if (e.isIntersecting) links.forEach(a => a.classList.toggle('is-active', a.dataset.spy === e.target.id)); });
  }, { rootMargin: '-45% 0px -50% 0px' });
  links.forEach(a => { const s = document.getElementById(a.dataset.spy); s && spy.observe(s); });

  const toggle = $('#navToggle'), menu = $('#navLinks');
  toggle?.addEventListener('click', () => {
    const open = menu.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', open);
  });
  menu?.addEventListener('click', e => { if (e.target.closest('a')) { menu.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); } });

  /* One rAF loop for scroll: progress bar, parallax, back-to-top */
  const bar = $('#progress'), toTop = $('.to-top');
  const par = $$('[data-speed]');
  let ticking = false;
  const onScroll = () => {
    const y = scrollY, max = document.documentElement.scrollHeight - innerHeight;
    bar.style.transform = `scaleX(${max > 0 ? y / max : 0})`;
    toTop.classList.toggle('is-show', y > innerHeight * .6);
    if (!reduce && y < innerHeight * 1.2) par.forEach(el => { el.style.transform = `translate3d(0,${y * parseFloat(el.dataset.speed)}px,0)`; });
    ticking = false;
  };
  addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(onScroll); } }, { passive: true });
  onScroll();

  /* Desktop-only: custom cursor, magnetic buttons, card tilt + glow */
  if (finePointer && !reduce) {
    document.body.classList.add('has-cursor');
    const cur = $('#cursor');
    let tx = 0, ty = 0, cx = 0, cy = 0;
    addEventListener('pointermove', e => { tx = e.clientX; ty = e.clientY; }, { passive: true });
    (function loop() { cx += (tx - cx) * .22; cy += (ty - cy) * .22; cur.style.transform = `translate3d(${cx}px,${cy}px,0)`; requestAnimationFrame(loop); })();
    $$('a,button,.card').forEach(el => {
      el.addEventListener('pointerenter', () => cur.classList.add('is-hover'));
      el.addEventListener('pointerleave', () => cur.classList.remove('is-hover'));
    });

    $$('.magnetic').forEach(el => {
      el.addEventListener('pointermove', e => {
        const b = el.getBoundingClientRect();
        el.style.transform = `translate(${(e.clientX - b.left - b.width / 2) * .22}px,${(e.clientY - b.top - b.height / 2) * .3}px)`;
      });
      el.addEventListener('pointerleave', () => { el.style.transform = ''; });
    });

    $$('.tilt').forEach(el => {
      el.addEventListener('pointermove', e => {
        const b = el.getBoundingClientRect(), px = (e.clientX - b.left) / b.width, py = (e.clientY - b.top) / b.height;
        el.style.setProperty('--mx', px * 100 + '%'); el.style.setProperty('--my', py * 100 + '%');
        el.style.transform = `perspective(900px) rotateX(${(.5 - py) * 5}deg) rotateY(${(px - .5) * 5}deg) translateY(-3px)`;
      });
      el.addEventListener('pointerleave', () => { el.style.transform = ''; });
    });
  }
  // skill bars fill when cards reveal
  $$('.skill').forEach(el => { const o = new MutationObserver(() => el.classList.toggle('is-in', el.classList.contains('is-in'))); o.observe(el, { attributes: true }); });

  /* Copy email */
  const copy = $('#copyMail'), toast = $('#toast');
  const say = msg => { toast.textContent = msg; toast.classList.add('is-show'); clearTimeout(say.t); say.t = setTimeout(() => toast.classList.remove('is-show'), 2000); };
  copy?.addEventListener('click', async () => {
    const mail = copy.dataset.email;
    try { await navigator.clipboard.writeText(mail); say('Email copied'); }
    catch (_) {
      const t = document.createElement('textarea'); t.value = mail; document.body.append(t); t.select();
      try { document.execCommand('copy'); say('Email copied'); } catch (__) { say('Copy failed. Select the email and copy it.'); }
      t.remove();
    }
  });

  /* Hero 3D orb (Three.js) with fallback */
  const canvas = $('#orb');
  const fallback = () => document.documentElement.classList.add('no-webgl');
  const initOrb = () => {
    if (!window.THREE || reduce) return fallback();
    let renderer;
    try { renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: !small, powerPreference: 'low-power' }); }
    catch (_) { return fallback(); }
    if (!renderer.getContext()) return fallback();

    renderer.setPixelRatio(Math.min(devicePixelRatio, small ? 1.25 : 2));
    const scene = new THREE.Scene();
    const cam = new THREE.PerspectiveCamera(45, 1, .1, 100); cam.position.z = 6;
    const group = new THREE.Group(); scene.add(group);

    const core = new THREE.Mesh(new THREE.IcosahedronGeometry(1.45, small ? 1 : 2),
      new THREE.MeshStandardMaterial({ color: 0x6366f1, metalness: .6, roughness: .25, flatShading: true }));
    const wire = new THREE.Mesh(new THREE.IcosahedronGeometry(1.85, 1),
      new THREE.MeshBasicMaterial({ color: 0x22d3ee, wireframe: true, transparent: true, opacity: .35 }));
    group.add(core, wire);
    const pts = new THREE.Points(new THREE.BufferGeometry(), new THREE.PointsMaterial({ color: 0x22d3ee, size: .03 }));
    const n = small ? 150 : 400, arr = new Float32Array(n * 3);
    for (let i = 0; i < n; i++) { const r = 2.4 + Math.random() * 2.2, a = Math.random() * 6.283, b = Math.acos(2 * Math.random() - 1);
      arr.set([r * Math.sin(b) * Math.cos(a), r * Math.sin(b) * Math.sin(a), r * Math.cos(b)], i * 3); }
    pts.geometry.setAttribute('position', new THREE.BufferAttribute(arr, 3)); group.add(pts);
    scene.add(new THREE.AmbientLight(0xffffff, .5));
    const l1 = new THREE.PointLight(0x22d3ee, 2.2, 20); l1.position.set(4, 3, 4); scene.add(l1);
    const l2 = new THREE.PointLight(0x6366f1, 2, 20); l2.position.set(-4, -2, 3); scene.add(l2);

    const resize = () => {
      const w = canvas.clientWidth, h = canvas.clientHeight; renderer.setSize(w, h, false);
      cam.aspect = w / h; cam.updateProjectionMatrix();
      group.position.x = w > 820 ? 2.1 : 0; group.position.y = w > 820 ? 0 : 1.2; group.scale.setScalar(w > 820 ? 1 : .75);
    };
    resize(); addEventListener('resize', resize);

    let mx = 0, my = 0, visible = true;
    addEventListener('pointermove', e => { mx = e.clientX / innerWidth - .5; my = e.clientY / innerHeight - .5; }, { passive: true });
    new IntersectionObserver(([e]) => { visible = e.isIntersecting; if (visible) loop(); }).observe(canvas);
    const clock = new THREE.Clock();
    function loop() {
      if (!visible) return;
      const t = clock.getElapsedTime();
      core.rotation.set(t * .18 + my * .6, t * .24 + mx * .8, 0); wire.rotation.set(-t * .1, t * .14, 0); pts.rotation.y = t * .05;
      group.position.y += ((small ? 1.2 : 0) + Math.sin(t) * .12 - group.position.y) * .05;
      renderer.render(scene, cam); requestAnimationFrame(loop);
    }
    loop();
  };
  window.addEventListener('load', initOrb);
})();
