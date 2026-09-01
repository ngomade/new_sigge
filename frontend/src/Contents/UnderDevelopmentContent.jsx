import React, { useState, useEffect, useRef, useMemo } from 'react';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { Canvas, useFrame } from '@react-three/fiber';
import {
  MdConstruction,
  MdEngineering,
  MdArrowBack,
  MdHourglassBottom
} from 'react-icons/md';

/* ============================================================
   HOOKS UTILITAIRES (mêmes patterns que la page login)
   ============================================================ */
function useIsMobile(breakpoint = 768) {
  const [isMobile, setIsMobile] = useState(
    () => typeof window !== 'undefined' && window.innerWidth < breakpoint
  );
  useEffect(() => {
    const onResize = () => setIsMobile(window.innerWidth < breakpoint);
    window.addEventListener('resize', onResize);
    return () => window.removeEventListener('resize', onResize);
  }, [breakpoint]);
  return isMobile;
}

function useMousePosition() {
  const pos = useRef({ x: 0, y: 0 });
  useEffect(() => {
    const handleMove = (e) => {
      pos.current.x = (e.clientX / window.innerWidth) * 2 - 1;
      pos.current.y = (e.clientY / window.innerHeight) * 2 - 1;
    };
    window.addEventListener('mousemove', handleMove);
    return () => window.removeEventListener('mousemove', handleMove);
  }, []);
  return pos;
}

/* ============================================================
   ENGRENAGES — pièce centrale de la scène
   ============================================================ */
function Gear({ position, radius = 1, teeth = 10, tube = 0.06, speed = 0.4, color, opacity = 0.5 }) {
  const groupRef = useRef(null);

  useFrame((_, delta) => {
    if (groupRef.current) groupRef.current.rotation.z += speed * delta;
  });

  const toothAngles = useMemo(
    () => Array.from({ length: teeth }, (_, i) => (i / teeth) * Math.PI * 2),
    [teeth]
  );
  const toothLen = radius * 0.22;

  return (
    <group ref={groupRef} position={position}>
      <mesh>
        <torusGeometry args={[radius * 0.78, tube, 8, 32]} />
        <meshBasicMaterial color={color} transparent opacity={opacity} />
      </mesh>
      <mesh>
        <circleGeometry args={[radius * 0.2, 16]} />
        <meshBasicMaterial color={color} wireframe transparent opacity={opacity * 0.75} />
      </mesh>
      {toothAngles.map((angle, i) => (
        <mesh
          key={i}
          position={[Math.cos(angle) * radius * 0.95, Math.sin(angle) * radius * 0.95, 0]}
          rotation={[0, 0, angle]}
        >
          <boxGeometry args={[toothLen, toothLen * 0.55, tube * 2]} />
          <meshBasicMaterial color={color} transparent opacity={opacity} />
        </mesh>
      ))}
    </group>
  );
}

/* ============================================================
   GRUE DE CHANTIER — bras qui oscille, crochet qui lève
   ============================================================ */
function Crane({ position, accent, accentGold }) {
  const armRef = useRef(null);
  const hookRef = useRef(null);

  useFrame(({ clock }) => {
    const t = clock.getElapsedTime();
    if (armRef.current) armRef.current.rotation.z = Math.sin(t * 0.3) * 0.07;
    if (hookRef.current) hookRef.current.position.y = -0.9 + Math.sin(t * 1.1) * 0.12;
  });

  return (
    <group position={position}>
      <mesh position={[0, 0.6, 0]}>
        <boxGeometry args={[0.08, 2.4, 0.08]} />
        <meshBasicMaterial color={accent} wireframe transparent opacity={0.45} />
      </mesh>
      <group ref={armRef} position={[0, 1.7, 0]}>
        <mesh position={[0.9, 0, 0]}>
          <boxGeometry args={[1.8, 0.06, 0.06]} />
          <meshBasicMaterial color={accent} wireframe transparent opacity={0.45} />
        </mesh>
        <mesh position={[-0.5, -0.08, 0]}>
          <boxGeometry args={[0.3, 0.2, 0.2]} />
          <meshBasicMaterial color={accentGold} transparent opacity={0.5} />
        </mesh>
        <mesh position={[1.5, -0.5, 0]}>
          <cylinderGeometry args={[0.006, 0.006, 1, 6]} />
          <meshBasicMaterial color={accent} transparent opacity={0.4} />
        </mesh>
        <mesh ref={hookRef} position={[1.5, -0.9, 0]}>
          <boxGeometry args={[0.2, 0.2, 0.2]} />
          <meshBasicMaterial color={accentGold} wireframe transparent opacity={0.65} />
        </mesh>
      </group>
    </group>
  );
}

/* ============================================================
   BLOCS QUI S'EMPILENT — chantier en cours de montage
   ============================================================ */
function BuildingBlocks({ accent, accentGold, count = 6 }) {
  const blocks = useMemo(
    () =>
      Array.from({ length: count }, (_, i) => ({
        x: (i - (count - 1) / 2) * 0.42,
        baseY: -1.7,
        phase: i * 0.55,
        size: 0.28 + (i % 2) * 0.06
      })),
    [count]
  );
  const refs = useRef([]);

  useFrame(({ clock }) => {
    const t = clock.getElapsedTime();
    blocks.forEach((b, i) => {
      const mesh = refs.current[i];
      if (!mesh) return;
      const cycle = ((t * 0.35 + b.phase) % 3) / 3;
      const rise = Math.min(1, cycle * 1.6);
      mesh.position.y = b.baseY + rise * (0.35 + i * 0.02);
      mesh.material.opacity = 0.22 + rise * 0.35;
    });
  });

  return (
    <group position={[0, 0, -2.6]}>
      {blocks.map((b, i) => (
        <mesh key={i} ref={(el) => (refs.current[i] = el)} position={[b.x, b.baseY, 0]}>
          <boxGeometry args={[b.size, b.size, b.size]} />
          <meshBasicMaterial color={i % 3 === 0 ? accentGold : accent} wireframe transparent opacity={0.3} />
        </mesh>
      ))}
    </group>
  );
}

/* ============================================================
   OUVRIERS — silhouettes stylisées qui travaillent en boucle
   ============================================================ */
function Worker({ position, accent, accentGold, tool = 'hammer', phase = 0, scale = 1 }) {
  const armRef = useRef(null);
  const bodyRef = useRef(null);

  useFrame(({ clock }) => {
    const t = clock.getElapsedTime() + phase;
    if (armRef.current) {
      if (tool === 'hammer') {
        // Coup de marteau répété : montée lente, frappe rapide
        armRef.current.rotation.x = -0.9 + Math.pow(Math.abs(Math.sin(t * 2.2)), 0.5) * 1.5;
      } else {
        // Geste de vissage / ajustement : va-et-vient latéral
        armRef.current.rotation.z = Math.sin(t * 3.4) * 0.35;
        armRef.current.rotation.x = -0.4 + Math.sin(t * 3.4) * 0.15;
      }
    }
    if (bodyRef.current) {
      bodyRef.current.position.y = Math.abs(Math.sin(t * (tool === 'hammer' ? 2.2 : 1.7))) * 0.025;
    }
  });

  return (
    <group position={position} scale={scale}>
      <group ref={bodyRef}>
        {/* Jambes */}
        <mesh position={[-0.07, -0.55, 0]}>
          <boxGeometry args={[0.09, 0.5, 0.12]} />
          <meshBasicMaterial color={accent} transparent opacity={0.55} />
        </mesh>
        <mesh position={[0.07, -0.55, 0]}>
          <boxGeometry args={[0.09, 0.5, 0.12]} />
          <meshBasicMaterial color={accent} transparent opacity={0.55} />
        </mesh>
        {/* Gilet chantier */}
        <mesh position={[0, -0.05, 0]}>
          <boxGeometry args={[0.32, 0.42, 0.2]} />
          <meshBasicMaterial color={accentGold} wireframe transparent opacity={0.65} />
        </mesh>
        {/* Tête */}
        <mesh position={[0, 0.32, 0]}>
          <sphereGeometry args={[0.12, 10, 10]} />
          <meshBasicMaterial color={accent} transparent opacity={0.6} />
        </mesh>
        {/* Casque */}
        <mesh position={[0, 0.4, 0]}>
          <cylinderGeometry args={[0.13, 0.13, 0.08, 10]} />
          <meshBasicMaterial color={accentGold} transparent opacity={0.8} />
        </mesh>
        {/* Bras statique */}
        <mesh position={[-0.22, 0.05, 0]} rotation={[0, 0, 0.3]}>
          <cylinderGeometry args={[0.035, 0.035, 0.32, 6]} />
          <meshBasicMaterial color={accent} transparent opacity={0.5} />
        </mesh>
        {/* Bras animé + outil */}
        <group position={[0.2, 0.18, 0]} ref={armRef}>
          <mesh position={[0, -0.16, 0]}>
            <cylinderGeometry args={[0.035, 0.035, 0.32, 6]} />
            <meshBasicMaterial color={accent} transparent opacity={0.5} />
          </mesh>
          {tool === 'hammer' ? (
            <group position={[0, -0.34, 0]} rotation={[0, 0, Math.PI / 2]}>
              <mesh>
                <cylinderGeometry args={[0.012, 0.012, 0.22, 6]} />
                <meshBasicMaterial color={accent} transparent opacity={0.6} />
              </mesh>
              <mesh position={[0.11, 0, 0]}>
                <boxGeometry args={[0.1, 0.045, 0.045]} />
                <meshBasicMaterial color={accentGold} transparent opacity={0.85} />
              </mesh>
            </group>
          ) : (
            <group position={[0, -0.34, 0]}>
              <mesh>
                <cylinderGeometry args={[0.012, 0.012, 0.28, 6]} />
                <meshBasicMaterial color={accent} transparent opacity={0.6} />
              </mesh>
              <mesh position={[0, -0.16, 0]} rotation={[0, 0, 0.5]}>
                <boxGeometry args={[0.12, 0.03, 0.06]} />
                <meshBasicMaterial color={accentGold} transparent opacity={0.85} />
              </mesh>
            </group>
          )}
        </group>
      </group>
    </group>
  );
}

/* ============================================================
   SOL / GRILLE DE CHANTIER + POUSSIÈRE
   ============================================================ */
function SiteGrid({ accent }) {
  return (
    <gridHelper
      args={[14, 22, accent, accent]}
      position={[0, -2.4, -2]}
      material-transparent
      material-opacity={0.14}
    />
  );
}

function Dust({ count, color }) {
  const positions = useMemo(() => {
    const arr = new Float32Array(count * 3);
    for (let i = 0; i < count; i++) {
      arr[i * 3] = (Math.random() - 0.5) * 16;
      arr[i * 3 + 1] = (Math.random() - 0.5) * 10;
      arr[i * 3 + 2] = (Math.random() - 0.5) * 8 - 4;
    }
    return arr;
  }, [count]);

  const ref = useRef(null);
  useFrame(({ clock }) => {
    if (ref.current) ref.current.rotation.y = clock.getElapsedTime() * 0.01;
  });

  return (
    <points ref={ref}>
      <bufferGeometry>
        <bufferAttribute attach="attributes-position" count={count} array={positions} itemSize={3} args={[positions, 3]} />
      </bufferGeometry>
      <pointsMaterial color={color} size={0.03} transparent opacity={0.45} sizeAttenuation />
    </points>
  );
}

/* ============================================================
   RIG DE SCÈNE — parallax souris (pas de scroll ici, page fixe)
   ============================================================ */
function SceneRig({ children, mouse }) {
  const groupRef = useRef(null);

  useFrame(() => {
    const group = groupRef.current;
    if (!group) return;
    group.rotation.y += (mouse.current.x * 0.12 - group.rotation.y) * 0.02;
    group.rotation.x += (-mouse.current.y * 0.08 - group.rotation.x) * 0.02;
  });

  return <group ref={groupRef}>{children}</group>;
}

function UnderDevelopmentBackground3D({ accent, accentGold }) {
  const isMobile = useIsMobile();
  const mouse = useMousePosition();

  return (
    <div style={{ position: 'absolute', inset: 0, zIndex: 0, pointerEvents: 'none', overflow: 'hidden' }}>
      <Canvas camera={{ position: [0, 0, 8], fov: 50 }} dpr={isMobile ? [1, 1] : [1, 1.5]}>
        <SceneRig mouse={mouse}>
          <Gear position={[-2.4, 0.9, -2]} radius={1.05} teeth={10} speed={0.35} color={accent} />
          <Gear position={[-0.9, 1.4, -2.6]} radius={0.62} teeth={8} speed={-0.55} color={accentGold} opacity={0.55} />
          {!isMobile && (
            <Gear position={[2.6, -1.3, -3.2]} radius={0.8} teeth={9} speed={0.42} color={accent} opacity={0.4} />
          )}
          <Crane position={isMobile ? [1.6, -1.4, -3] : [2.9, -0.9, -3]} accent={accent} accentGold={accentGold} />
          <BuildingBlocks accent={accent} accentGold={accentGold} count={isMobile ? 4 : 6} />
          <Worker
            position={isMobile ? [-1.5, -1.55, -1.1] : [-2.15, -1.35, -1.2]}
            accent={accent}
            accentGold={accentGold}
            tool="hammer"
            phase={0}
            scale={isMobile ? 1.2 : 1.4}
          />
          {!isMobile && (
            <Worker
              position={[2.4, -1.25, -1.5]}
              accent={accent}
              accentGold={accentGold}
              tool="wrench"
              phase={1.4}
              scale={1.25}
            />
          )}
          <SiteGrid accent={accent} />
          <Dust count={isMobile ? 35 : 100} color={accent} />
        </SceneRig>
      </Canvas>
    </div>
  );
}

/* ============================================================
   COMPOSANT PRINCIPAL
   ============================================================ */
export default function UnderDevelopmentContent({ backHref = '/' }) {
  const ACCENT = 'var(--app-primary)';
  const ACCENT_DARK = 'var(--app-primary-dark)';
  const ACCENT_GOLD = 'var(--app-accent)';

  const pageBg = 'var(--app-bg)';
  const cardBg = 'var(--app-surface)';
  const cardBorder = '1px solid var(--app-border)';
  const cardShadow = '0 20px 40px var(--app-shadow), inset 0 1px 0 rgba(255, 255, 255, 0.5)';

  useEffect(() => {
    AOS.init({ duration: 1000, once: true, easing: 'ease-out-quart' });
  }, []);

  return (
    <div
      className="under-dev-wrapper d-flex align-items-center justify-content-center position-relative overflow-hidden px-3 py-4"
      style={{ minHeight: '100vh', backgroundColor: pageBg, transition: 'background-color 0.3s ease' }}
    >
      <style>{`
        @keyframes udProgressStripes {
          0% { background-position: 0 0; }
          100% { background-position: 40px 0; }
        }
        @keyframes udPulse {
          0%, 100% { opacity: 1; transform: scale(1); }
          50% { opacity: 0.55; transform: scale(0.85); }
        }
      `}</style>

      <UnderDevelopmentBackground3D accent="#0E8F74" accentGold="#F5C84C" />

      <div
        className="position-absolute top-50 start-50 translate-middle rounded-circle"
        style={{
          width: 'min(450px, 90vw)',
          height: 'min(450px, 90vw)',
          background: 'radial-gradient(circle, rgba(245, 200, 76, 0.12) 0%, transparent 70%)',
          filter: 'blur(50px)',
          zIndex: 0,
          pointerEvents: 'none'
        }}
      ></div>

      <div
        className="under-dev-card p-4 shadow-2xl rounded-4 border-0 position-relative w-100 mx-auto text-center"
        data-aos="zoom-in-up"
        style={{ maxWidth: '400px', backgroundColor: cardBg, border: cardBorder, zIndex: 1, boxShadow: cardShadow }}
      >
        <div
          className="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
          data-aos="fade-down"
          data-aos-delay="150"
          style={{
            width: 64,
            height: 64,
            backgroundColor: 'var(--app-primary-soft)',
            color: ACCENT_DARK,
            fontSize: '1.8rem',
            animation: 'udPulse 2.4s ease-in-out infinite'
          }}
        >
          <MdConstruction />
        </div>

        <h2 className="fw-bold fs-4 mb-2" data-aos="fade-up" data-aos-delay="220" style={{ color: ACCENT_DARK }}>
          Page en cours de construction
        </h2>

        <p
          className="small mb-3"
          data-aos="fade-up"
          data-aos-delay="280"
          style={{ color: 'var(--app-text-muted)' }}
        >
          Nous sommes en train de bâtir cette fonctionnalité pour vous offrir la meilleure expérience possible.
          Merci de votre patience.
        </p>

        <div
          className="d-flex align-items-center justify-content-center gap-2 mb-3 small fw-medium"
          data-aos="fade-up"
          data-aos-delay="330"
          style={{ color: ACCENT }}
        >
          <MdEngineering size={16} />
          <span>Travaux en cours</span>
          <MdHourglassBottom size={14} style={{ opacity: 0.7 }} />
        </div>

        <div
          data-aos="fade-up"
          data-aos-delay="380"
          style={{
            height: 8,
            borderRadius: 999,
            overflow: 'hidden',
            backgroundColor: 'var(--app-surface-alt)',
            border: '1px solid var(--app-border)',
            marginBottom: '1.5rem'
          }}
        >
          <div
            style={{
              height: '100%',
              width: '60%',
              borderRadius: 999,
              backgroundImage: `repeating-linear-gradient(45deg, ${ACCENT} 0 10px, ${ACCENT_GOLD} 10px 20px)`,
              backgroundSize: '40px 100%',
              animation: 'udProgressStripes 1.2s linear infinite'
            }}
          ></div>
        </div>

        <a
          href={backHref}
          data-aos="fade-up"
          data-aos-delay="430"
          className="btn rounded-pill fw-bold d-inline-flex align-items-center justify-content-center gap-2"
          style={{
            backgroundColor: 'transparent',
            color: 'var(--app-text)',
            border: '1px solid var(--app-border)',
            fontSize: '0.85rem',
            height: '44px',
            padding: '0 20px',
            textDecoration: 'none',
            transition: 'all 0.2s ease-in-out'
          }}
          onMouseEnter={(e) => {
            e.currentTarget.style.backgroundColor = 'var(--app-primary-soft)';
            e.currentTarget.style.borderColor = 'var(--app-primary)';
          }}
          onMouseLeave={(e) => {
            e.currentTarget.style.backgroundColor = 'transparent';
            e.currentTarget.style.borderColor = 'var(--app-border)';
          }}
        >
          <MdArrowBack size={16} /> Retour à l'accueil
        </a>
      </div>
    </div>
  );
}