@extends("sige_app.frontend.template.frontend")
@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* ============================================================
       Scène 3D vanilla Three.js (équivalent de UnderDevelopmentBackground3D)
       ============================================================ */
    const container = document.getElementById('ud-canvas-container');
    if (!container || typeof THREE === 'undefined') return;

    const ACCENT = 0x0E8F74;
    const ACCENT_GOLD = 0xF5C84C;
    const isMobile = window.innerWidth < 768;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(50, container.clientWidth / container.clientHeight, 0.1, 100);
    camera.position.set(0, 0, 8);

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setPixelRatio(isMobile ? 1 : Math.min(window.devicePixelRatio, 1.5));
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    const rig = new THREE.Group();
    scene.add(rig);

    /* --- Engrenage --- */
    function createGear(radius, teeth, tube, color, opacity) {
        const group = new THREE.Group();

        const torusGeo = new THREE.TorusGeometry(radius * 0.78, tube, 8, 32);
        const torusMat = new THREE.MeshBasicMaterial({ color, transparent: true, opacity });
        group.add(new THREE.Mesh(torusGeo, torusMat));

        const circleGeo = new THREE.CircleGeometry(radius * 0.2, 16);
        const circleMat = new THREE.MeshBasicMaterial({ color, wireframe: true, transparent: true, opacity: opacity * 0.75 });
        group.add(new THREE.Mesh(circleGeo, circleMat));

        const toothLen = radius * 0.22;
        for (let i = 0; i < teeth; i++) {
            const angle = (i / teeth) * Math.PI * 2;
            const toothGeo = new THREE.BoxGeometry(toothLen, toothLen * 0.55, tube * 2);
            const toothMat = new THREE.MeshBasicMaterial({ color, transparent: true, opacity });
            const tooth = new THREE.Mesh(toothGeo, toothMat);
            tooth.position.set(Math.cos(angle) * radius * 0.95, Math.sin(angle) * radius * 0.95, 0);
            tooth.rotation.z = angle;
            group.add(tooth);
        }
        return group;
    }

    const gears = [
        { mesh: createGear(1.05, 10, 0.06, ACCENT, 0.5), pos: [-2.4, 0.9, -2], speed: 0.35 },
        { mesh: createGear(0.62, 8, 0.06, ACCENT_GOLD, 0.55), pos: [-0.9, 1.4, -2.6], speed: -0.55 }
    ];
    if (!isMobile) {
        gears.push({ mesh: createGear(0.8, 9, 0.06, ACCENT, 0.4), pos: [2.6, -1.3, -3.2], speed: 0.42 });
    }
    gears.forEach(g => {
        g.mesh.position.set(...g.pos);
        rig.add(g.mesh);
    });

    /* --- Grue de chantier --- */
    function createCrane(accent, accentGold) {
        const group = new THREE.Group();

        const mast = new THREE.Mesh(
            new THREE.BoxGeometry(0.08, 2.4, 0.08),
            new THREE.MeshBasicMaterial({ color: accent, wireframe: true, transparent: true, opacity: 0.45 })
        );
        mast.position.set(0, 0.6, 0);
        group.add(mast);

        const arm = new THREE.Group();
        arm.position.set(0, 1.7, 0);

        const beam = new THREE.Mesh(
            new THREE.BoxGeometry(1.8, 0.06, 0.06),
            new THREE.MeshBasicMaterial({ color: accent, wireframe: true, transparent: true, opacity: 0.45 })
        );
        beam.position.set(0.9, 0, 0);
        arm.add(beam);

        const counterweight = new THREE.Mesh(
            new THREE.BoxGeometry(0.3, 0.2, 0.2),
            new THREE.MeshBasicMaterial({ color: accentGold, transparent: true, opacity: 0.5 })
        );
        counterweight.position.set(-0.5, -0.08, 0);
        arm.add(counterweight);

        const cable = new THREE.Mesh(
            new THREE.CylinderGeometry(0.006, 0.006, 1, 6),
            new THREE.MeshBasicMaterial({ color: accent, transparent: true, opacity: 0.4 })
        );
        cable.position.set(1.5, -0.5, 0);
        arm.add(cable);

        const hook = new THREE.Mesh(
            new THREE.BoxGeometry(0.2, 0.2, 0.2),
            new THREE.MeshBasicMaterial({ color: accentGold, wireframe: true, transparent: true, opacity: 0.65 })
        );
        hook.position.set(1.5, -0.9, 0);
        arm.add(hook);

        group.add(arm);
        return { group, arm, hook };
    }

    const crane = createCrane(ACCENT, ACCENT_GOLD);
    crane.group.position.set(...(isMobile ? [1.6, -1.4, -3] : [2.9, -0.9, -3]));
    rig.add(crane.group);

    /* --- Blocs qui s'empilent --- */
    const blockCount = isMobile ? 4 : 6;
    const blocksGroup = new THREE.Group();
    blocksGroup.position.set(0, 0, -2.6);
    const blocks = [];
    for (let i = 0; i < blockCount; i++) {
        const size = 0.28 + (i % 2) * 0.06;
        const mat = new THREE.MeshBasicMaterial({
            color: i % 3 === 0 ? ACCENT_GOLD : ACCENT,
            wireframe: true,
            transparent: true,
            opacity: 0.3
        });
        const mesh = new THREE.Mesh(new THREE.BoxGeometry(size, size, size), mat);
        const baseY = -1.7;
        mesh.position.set((i - (blockCount - 1) / 2) * 0.42, baseY, 0);
        blocksGroup.add(mesh);
        blocks.push({ mesh, baseY, phase: i * 0.55, index: i });
    }
    rig.add(blocksGroup);

    /* --- Ouvrier stylisé --- */
    function createWorker(accent, accentGold, tool, scale) {
        const group = new THREE.Group();
        group.scale.set(scale, scale, scale);
        const body = new THREE.Group();
        group.add(body);

        const legMat = new THREE.MeshBasicMaterial({ color: accent, transparent: true, opacity: 0.55 });
        const leg1 = new THREE.Mesh(new THREE.BoxGeometry(0.09, 0.5, 0.12), legMat);
        leg1.position.set(-0.07, -0.55, 0);
        const leg2 = new THREE.Mesh(new THREE.BoxGeometry(0.09, 0.5, 0.12), legMat);
        leg2.position.set(0.07, -0.55, 0);
        body.add(leg1, leg2);

        const vest = new THREE.Mesh(
            new THREE.BoxGeometry(0.32, 0.42, 0.2),
            new THREE.MeshBasicMaterial({ color: accentGold, wireframe: true, transparent: true, opacity: 0.65 })
        );
        vest.position.set(0, -0.05, 0);
        body.add(vest);

        const head = new THREE.Mesh(
            new THREE.SphereGeometry(0.12, 10, 10),
            new THREE.MeshBasicMaterial({ color: accent, transparent: true, opacity: 0.6 })
        );
        head.position.set(0, 0.32, 0);
        body.add(head);

        const helmet = new THREE.Mesh(
            new THREE.CylinderGeometry(0.13, 0.13, 0.08, 10),
            new THREE.MeshBasicMaterial({ color: accentGold, transparent: true, opacity: 0.8 })
        );
        helmet.position.set(0, 0.4, 0);
        body.add(helmet);

        const staticArm = new THREE.Mesh(
            new THREE.CylinderGeometry(0.035, 0.035, 0.32, 6),
            new THREE.MeshBasicMaterial({ color: accent, transparent: true, opacity: 0.5 })
        );
        staticArm.position.set(-0.22, 0.05, 0);
        staticArm.rotation.z = 0.3;
        body.add(staticArm);

        const arm = new THREE.Group();
        arm.position.set(0.2, 0.18, 0);
        const upperArm = new THREE.Mesh(
            new THREE.CylinderGeometry(0.035, 0.035, 0.32, 6),
            new THREE.MeshBasicMaterial({ color: accent, transparent: true, opacity: 0.5 })
        );
        upperArm.position.set(0, -0.16, 0);
        arm.add(upperArm);

        const toolGroup = new THREE.Group();
        if (tool === 'hammer') {
            toolGroup.position.set(0, -0.34, 0);
            toolGroup.rotation.z = Math.PI / 2;
            const handle = new THREE.Mesh(
                new THREE.CylinderGeometry(0.012, 0.012, 0.22, 6),
                new THREE.MeshBasicMaterial({ color: accent, transparent: true, opacity: 0.6 })
            );
            toolGroup.add(handle);
            const head2 = new THREE.Mesh(
                new THREE.BoxGeometry(0.1, 0.045, 0.045),
                new THREE.MeshBasicMaterial({ color: accentGold, transparent: true, opacity: 0.85 })
            );
            head2.position.set(0.11, 0, 0);
            toolGroup.add(head2);
        } else {
            toolGroup.position.set(0, -0.34, 0);
            const handle = new THREE.Mesh(
                new THREE.CylinderGeometry(0.012, 0.012, 0.28, 6),
                new THREE.MeshBasicMaterial({ color: accent, transparent: true, opacity: 0.6 })
            );
            toolGroup.add(handle);
            const wrenchHead = new THREE.Mesh(
                new THREE.BoxGeometry(0.12, 0.03, 0.06),
                new THREE.MeshBasicMaterial({ color: accentGold, transparent: true, opacity: 0.85 })
            );
            wrenchHead.position.set(0, -0.16, 0);
            wrenchHead.rotation.z = 0.5;
            toolGroup.add(wrenchHead);
        }
        arm.add(toolGroup);
        body.add(arm);

        return { group, body, arm };
    }

    const workers = [];
    const worker1 = createWorker(ACCENT, ACCENT_GOLD, 'hammer', isMobile ? 1.2 : 1.4);
    worker1.group.position.set(...(isMobile ? [-1.5, -1.55, -1.1] : [-2.15, -1.35, -1.2]));
    worker1.tool = 'hammer';
    worker1.phase = 0;
    rig.add(worker1.group);
    workers.push(worker1);

    if (!isMobile) {
        const worker2 = createWorker(ACCENT, ACCENT_GOLD, 'wrench', 1.25);
        worker2.group.position.set(2.4, -1.25, -1.5);
        worker2.tool = 'wrench';
        worker2.phase = 1.4;
        rig.add(worker2.group);
        workers.push(worker2);
    }

    /* --- Grille de sol --- */
    const grid = new THREE.GridHelper(14, 22, ACCENT, ACCENT);
    grid.position.set(0, -2.4, -2);
    grid.material.transparent = true;
    grid.material.opacity = 0.14;
    rig.add(grid);

    /* --- Poussière --- */
    const dustCount = isMobile ? 35 : 100;
    const dustPositions = new Float32Array(dustCount * 3);
    for (let i = 0; i < dustCount; i++) {
        dustPositions[i * 3] = (Math.random() - 0.5) * 16;
        dustPositions[i * 3 + 1] = (Math.random() - 0.5) * 10;
        dustPositions[i * 3 + 2] = (Math.random() - 0.5) * 8 - 4;
    }
    const dustGeo = new THREE.BufferGeometry();
    dustGeo.setAttribute('position', new THREE.BufferAttribute(dustPositions, 3));
    const dustMat = new THREE.PointsMaterial({ color: ACCENT, size: 0.03, transparent: true, opacity: 0.45, sizeAttenuation: true });
    const dust = new THREE.Points(dustGeo, dustMat);
    rig.add(dust);

    /* --- Parallax souris --- */
    const mouse = { x: 0, y: 0 };
    window.addEventListener('mousemove', function (e) {
        mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
        mouse.y = (e.clientY / window.innerHeight) * 2 - 1;
    });

    /* --- Boucle d'animation --- */
    const clock = new THREE.Clock();
    function animate() {
        requestAnimationFrame(animate);
        const t = clock.getElapsedTime();
        const delta = clock.getDelta();

        gears.forEach(g => { g.mesh.rotation.z += g.speed * delta; });

        crane.arm.rotation.z = Math.sin(t * 0.3) * 0.07;
        crane.hook.position.y = -0.9 + Math.sin(t * 1.1) * 0.12;

        blocks.forEach(b => {
            const cycle = ((t * 0.35 + b.phase) % 3) / 3;
            const rise = Math.min(1, cycle * 1.6);
            b.mesh.position.y = b.baseY + rise * (0.35 + b.index * 0.02);
            b.mesh.material.opacity = 0.22 + rise * 0.35;
        });

        workers.forEach(w => {
            const tt = t + w.phase;
            if (w.tool === 'hammer') {
                w.arm.rotation.x = -0.9 + Math.pow(Math.abs(Math.sin(tt * 2.2)), 0.5) * 1.5;
                w.body.position.y = Math.abs(Math.sin(tt * 2.2)) * 0.025;
            } else {
                w.arm.rotation.z = Math.sin(tt * 3.4) * 0.35;
                w.arm.rotation.x = -0.4 + Math.sin(tt * 3.4) * 0.15;
                w.body.position.y = Math.abs(Math.sin(tt * 1.7)) * 0.025;
            }
        });

        dust.rotation.y = t * 0.01;

        rig.rotation.y += (mouse.x * 0.12 - rig.rotation.y) * 0.02;
        rig.rotation.x += (-mouse.y * 0.08 - rig.rotation.x) * 0.02;

        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', function () {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });

    /* --- Effet hover sur le bouton retour (équivalent onMouseEnter/onMouseLeave) --- */
    const backBtn = document.getElementById('ud-back-btn');
    if (backBtn) {
        backBtn.addEventListener('mouseenter', function () {
            backBtn.style.backgroundColor = 'var(--app-primary-soft)';
            backBtn.style.borderColor = 'var(--app-primary)';
        });
        backBtn.addEventListener('mouseleave', function () {
            backBtn.style.backgroundColor = 'transparent';
            backBtn.style.borderColor = 'var(--app-border)';
        });
    }

    /* --- AOS --- */
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 1000, once: true, easing: 'ease-out-quart' });
    }
});
</script>
@endsection
@section('content')
<style>
@keyframes udProgressStripes {
    0% { background-position: 0 0; }
    100% { background-position: 40px 0; }
}
@keyframes udPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.55; transform: scale(0.85); }
}
.under-dev-wrapper {
    min-height: 100vh;
    background-color: var(--app-bg);
    transition: background-color 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: 1.5rem 1rem;
}
#ud-canvas-container {
    position: absolute;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
}
.ud-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: min(450px, 90vw);
    height: min(450px, 90vw);
    background: radial-gradient(circle, rgba(245, 200, 76, 0.12) 0%, transparent 70%);
    filter: blur(50px);
    z-index: 0;
    pointer-events: none;
}
.under-dev-card {
    max-width: 400px;
    width: 100%;
    margin: 0 auto;
    text-align: center;
    background-color: var(--app-surface);
    border: 1px solid var(--app-border);
    border-radius: 1.5rem;
    box-shadow: 0 20px 40px var(--app-shadow), inset 0 1px 0 rgba(255, 255, 255, 0.5);
    padding: 1.5rem;
    position: relative;
    z-index: 1;
}
.ud-icon-badge {
    width: 64px;
    height: 64px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: var(--app-primary-soft);
    color: var(--app-primary-dark);
    font-size: 1.8rem;
    animation: udPulse 2.4s ease-in-out infinite;
    margin-bottom: 1rem;
}
.ud-progress-track {
    height: 8px;
    border-radius: 999px;
    overflow: hidden;
    background-color: var(--app-surface-alt);
    border: 1px solid var(--app-border);
    margin-bottom: 1.5rem;
}
.ud-progress-fill {
    height: 100%;
    width: 60%;
    border-radius: 999px;
    background-image: repeating-linear-gradient(45deg, var(--app-primary) 0 10px, var(--app-accent) 10px 20px);
    background-size: 40px 100%;
    animation: udProgressStripes 1.2s linear infinite;
}
.ud-back-btn {
    background-color: transparent;
    color: var(--app-text);
    border: 1px solid var(--app-border);
    font-size: 0.85rem;
    height: 44px;
    padding: 0 20px;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}
</style>

<div class="under-dev-wrapper">
    <div id="ud-canvas-container"></div>
    <div class="ud-glow"></div>

    <div class="under-dev-card rounded-4" data-aos="zoom-in-up">
        <div class="ud-icon-badge" data-aos="fade-down" data-aos-delay="150">
            <i class="bi bi-cone-striped"></i>
        </div>

        <h2 class="fw-bold fs-4 mb-2" data-aos="fade-up" data-aos-delay="220" style="color: var(--app-primary-dark);">
            Page en cours de construction
        </h2>

        <p class="small mb-3" data-aos="fade-up" data-aos-delay="280" style="color: var(--app-text-muted);">
            Nous sommes en train de bâtir cette fonctionnalité pour vous offrir la meilleure expérience possible.
            Merci de votre patience.
        </p>

        <div class="d-flex align-items-center justify-content-center gap-2 mb-3 small fw-medium" data-aos="fade-up" data-aos-delay="330" style="color: var(--app-primary);">
            <i class="bi bi-hammer" style="font-size: 0.9rem;"></i>
            <span>Travaux en cours</span>
            <i class="bi bi-hourglass-split" style="font-size: 0.8rem; opacity: 0.7;"></i>
        </div>

        <div class="ud-progress-track" data-aos="fade-up" data-aos-delay="380">
            <div class="ud-progress-fill"></div>
        </div>

        <a href="{{ url('/') }}" id="ud-back-btn" class="btn ud-back-btn rounded-pill fw-bold" data-aos="fade-up" data-aos-delay="430">
            <i class="bi bi-arrow-left" style="font-size: 0.9rem;"></i> Retour à l'accueil
        </a>
    </div>
</div>
@endsection