import React, { useState, useEffect, useRef, useMemo } from 'react';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { Canvas, useFrame } from '@react-three/fiber';
import * as THREE from 'three';
import {
  MdPerson,
  MdLock,
  MdEmail,
  MdArrowForward,
  MdArrowBack,
  MdErrorOutline,
  MdCheckCircleOutline,
  MdHelpOutline
} from 'react-icons/md';

/* ============================================================
   HOOKS UTILITAIRES
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

function useScrollDepth() {
  const depth = useRef(0);
  useEffect(() => {
    const handleScroll = () => {
      const max = document.documentElement.scrollHeight - window.innerHeight;
      depth.current = max > 0 ? Math.min(1, Math.max(0, window.scrollY / max)) : 0;
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);
  return depth;
}

/* ============================================================
   FOND 3D — HUB D'ACCÈS HOLOGRAPHIQUE (Adapté au vert)
   ============================================================ */
function AccessHub({ accent }) {
  const coreRef = useRef(null);
  const ring1 = useRef(null);
  const ring2 = useRef(null);

  useFrame(({ clock }) => {
    const t = clock.getElapsedTime();
    if (coreRef.current) {
      coreRef.current.rotation.z = t * 0.3;
      coreRef.current.scale.setScalar(1 + Math.sin(t * 1.4) * 0.05);
    }
    if (ring1.current) ring1.current.rotation.z = t * 0.2;
    if (ring2.current) ring2.current.rotation.z = -t * 0.15;
  });

  return (
    <group position={[0, 0, -2]}>
      <mesh ref={coreRef}>
        <cylinderGeometry args={[0.9, 0.9, 0.08, 6]} />
        <meshBasicMaterial color={accent} wireframe transparent opacity={0.5} />
      </mesh>
      <mesh ref={ring1} rotation={[Math.PI / 2, 0, 0]}>
        <torusGeometry args={[1.5, 0.015, 8, 60]} />
        <meshBasicMaterial color={accent} transparent opacity={0.35} />
      </mesh>
      <mesh ref={ring2} rotation={[Math.PI / 2, 0, 0]}>
        <torusGeometry args={[2, 0.01, 8, 60]} />
        <meshBasicMaterial color={accent} transparent opacity={0.2} />
      </mesh>
    </group>
  );
}

function AccessBadge({ position, speed, accent }) {
  const ref = useRef(null);

  useFrame(({ clock }) => {
    const mesh = ref.current;
    if (!mesh) return;
    const t = clock.getElapsedTime();
    mesh.rotation.y = t * speed;
    mesh.rotation.x = Math.sin(t * speed * 0.6) * 0.3;
    mesh.position.y = position[1] + Math.sin(t * speed * 0.8) * 0.35;
  });

  return (
    <mesh ref={ref} position={position}>
      <cylinderGeometry args={[0.45, 0.45, 0.1, 6]} />
      <meshBasicMaterial color={accent} wireframe transparent opacity={0.45} />
    </mesh>
  );
}

function ScanBeam({ accent }) {
  const ref = useRef(null);

  useFrame(({ clock }) => {
    const mesh = ref.current;
    if (!mesh) return;
    const t = clock.getElapsedTime();
    const cycle = (t * 0.35) % 2;
    const triangle = cycle > 1 ? 2 - cycle : cycle;
    mesh.position.y = (triangle - 0.5) * 4;
    mesh.material.opacity = 0.18 + 0.1 * Math.sin(t * 3);
  });

  return (
    <mesh ref={ref} position={[0, 0, -1.4]}>
      <planeGeometry args={[6, 0.03]} />
      <meshBasicMaterial color={accent} transparent opacity={0.25} blending={THREE.AdditiveBlending} depthWrite={false} />
    </mesh>
  );
}

function ScanGrid({ accent }) {
  return (
    <gridHelper
      args={[14, 22, accent, accent]}
      position={[0, -2.6, -2]}
      material-transparent
      material-opacity={0.15}
    />
  );
}

function Particles({ count, accent }) {
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
    if (ref.current) ref.current.rotation.y = clock.getElapsedTime() * 0.015;
  });

  return (
    <points ref={ref}>
      <bufferGeometry>
        <bufferAttribute attach="attributes-position" count={count} array={positions} itemSize={3} args={[positions, 3]} />
      </bufferGeometry>
      <pointsMaterial color={accent} size={0.03} transparent opacity={0.5} sizeAttenuation />
    </points>
  );
}

function SceneRig({ children, mouse, scroll }) {
  const groupRef = useRef(null);

  useFrame(({ camera }) => {
    const group = groupRef.current;
    if (!group) return;
    group.rotation.y += (mouse.current.x * 0.15 - group.rotation.y) * 0.02;
    group.rotation.x += (-mouse.current.y * 0.1 - group.rotation.x) * 0.02;
    const targetZ = 8 - scroll.current * 1.4;
    camera.position.z += (targetZ - camera.position.z) * 0.03;
  });

  return <group ref={groupRef}>{children}</group>;
}

function AccessGateBackground3D({ accent }) {
  const isMobile = useIsMobile();
  const mouse = useMousePosition();
  const scroll = useScrollDepth();

  const badgePositions = useMemo(
    () => [
      [-3.4, 1.3, -3],
      [3.6, -1.1, -3.5],
      [2.4, 2.1, -4.2]
    ],
    []
  );
  const visibleBadges = isMobile ? badgePositions.slice(0, 2) : badgePositions;

  return (
    <div style={{ position: 'absolute', inset: 0, zIndex: 0, pointerEvents: 'none', overflow: 'hidden' }}>
      <Canvas camera={{ position: [0, 0, 8], fov: 50 }} dpr={isMobile ? [1, 1] : [1, 1.5]}>
        <SceneRig mouse={mouse} scroll={scroll}>
          <AccessHub accent={accent} />
          <ScanBeam accent={accent} />
          <ScanGrid accent={accent} />
          {visibleBadges.map((pos, i) => (
            <AccessBadge key={i} position={pos} speed={0.3 + i * 0.1} accent={accent} />
          ))}
          <Particles count={isMobile ? 40 : 120} accent={accent} />
        </SceneRig>
      </Canvas>
    </div>
  );
}

/* ============================================================
   UTIL — récupération du token CSRF Blade
   ============================================================ */
function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

/* ============================================================
   COMPOSANT PRINCIPAL
   ============================================================ */
export default function LoginContent() {
  const ACCENT = '#0E8F74'; // --app-primary
  const ACCENT_HOVER = '#113D35'; // --app-primary-dark
  const GREEN = 'var(--app-success)';
  const RED = 'var(--app-danger)';

  const pageBg = 'var(--app-bg)';
  const cardBg = 'var(--app-surface)';
  const cardBorder = '1px solid var(--app-border)';
  const cardShadow = '0 20px 40px var(--app-shadow), inset 0 1px 0 rgba(255, 255, 255, 0.5)';
  const fieldBg = 'var(--app-surface-alt)';
  const fieldBgFocus = 'var(--app-surface)';

  const [mode, setMode] = useState('login');
  const [loginData, setLoginData] = useState({ login_user: '', pwd_user: '' });
  const [recoveryData, setRecoveryData] = useState({ nom_user: '', email_user: '' });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState(null);

  useEffect(() => {
    AOS.init({ duration: 1000, once: true, easing: 'ease-out-quart' });
  }, []);

  const switchMode = (next) => {
    setMode(next);
    setMessage(null);
  };

  const handleLoginChange = (e) => {
    const { name, value } = e.target;
    setLoginData((prev) => ({ ...prev, [name]: value }));
  };

  const handleRecoveryChange = (e) => {
    const { name, value } = e.target;
    setRecoveryData((prev) => ({ ...prev, [name]: value }));
  };

  const handleLogin = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage(null);
    try {
      const res = await fetch('/login', {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(loginData)
      });
      const data = await res.json().catch(() => null);
      if (!res.ok) throw new Error(data?.message || 'Matricule ou mot de passe incorrect.');
      setMessage({ type: 'success', text: data?.message || 'Connexion réussie, redirection…' });
      window.setTimeout(() => {
        window.location.href = data?.redirect || '/';
      }, 800);
    } catch (err) {
      setMessage({ type: 'error', text: err.message || 'Une erreur est survenue.' });
    } finally {
      setLoading(false);
    }
  };

  const handleRecovery = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage(null);
    try {
      const res = await fetch('/recuperation_pwd', {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(recoveryData)
      });
      const data = await res.json().catch(() => null);
      if (!res.ok) throw new Error(data?.message || 'Impossible de traiter la demande.');
      setMessage({ type: 'success', text: data?.message || 'Vos identifiants vous ont été envoyés par email.' });
    } catch (err) {
      setMessage({ type: 'error', text: err.message || 'Une erreur est survenue.' });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div
      className="login-page-wrapper d-flex align-items-center justify-content-center position-relative overflow-hidden px-3 py-4"
      style={{ minHeight: '100vh', backgroundColor: pageBg, transition: 'background-color 0.3s ease' }}
    >
      <AccessGateBackground3D accent={ACCENT} />

      <div
        className="position-absolute top-50 start-50 translate-middle rounded-circle"
        style={{
          width: 'min(450px, 90vw)',
          height: 'min(450px, 90vw)',
          background: 'radial-gradient(circle, rgba(14, 143, 116, 0.12) 0%, transparent 70%)',
          filter: 'blur(50px)',
          zIndex: 0,
          pointerEvents: 'none'
        }}
      ></div>

      <div
        className="login-card p-3 p-md-4 shadow-2xl rounded-4 border-0 position-relative w-100 mx-auto"
        data-aos="zoom-in-up"
        style={{ maxWidth: '380px', backgroundColor: cardBg, border: cardBorder, zIndex: 1, boxShadow: cardShadow }}
      >
        <div className="text-center mb-3" data-aos="fade-down" data-aos-delay="200">
          <h2 className="fw-bold fs-4 mb-1" style={{ color: 'var(--app-primary-dark)' }}>
            {mode === 'login' ? 'Authentification' : 'Récupération de mot de passe'}
          </h2>
          <p className="small mb-0" style={{ color: 'var(--app-text-muted)' }}>
            Espace étudiant <span className="fw-bold" style={{ color: ACCENT }}>ESTLC</span>
          </p>
        </div>

        {message && (
          <div
            className="d-flex align-items-center p-2 mb-3 rounded-3"
            data-aos="fade"
            style={{
              backgroundColor: message.type === 'success' ? 'var(--app-primary-soft)' : 'rgba(217, 95, 95, 0.1)',
              borderLeft: `4px solid ${message.type === 'success' ? GREEN : RED}`,
              color: message.type === 'success' ? ACCENT_HOVER : RED
            }}
          >
            <span className="fs-5 me-2">
              {message.type === 'success' ? <MdCheckCircleOutline /> : <MdErrorOutline />}
            </span>
            <span className="small fw-medium">{message.text}</span>
          </div>
        )}

        {mode === 'login' ? (
          <form onSubmit={handleLogin} className="login-form">
            <div className="mb-3" data-aos="fade-up" data-aos-delay="300">
              <label htmlFor="login-user" className="form-label small fw-bold mb-1" style={{ color: 'var(--app-text-muted)' }}>
                Matricule
              </label>
              <div className="input-group input-group-sm">
                <span className="input-group-text border-0 px-2" style={{ backgroundColor: fieldBg, color: 'var(--app-text-muted)' }}>
                  <MdPerson />
                </span>
                <input
                  id="login-user"
                  type="text"
                  className="form-control border-0 fs-6 shadow-none"
                  style={{ borderRadius: '0 8px 8px 0', backgroundColor: fieldBg, color: 'var(--app-text)', transition: 'all 0.3s ease' }}
                  onFocus={(e) => {
                    e.currentTarget.style.backgroundColor = fieldBgFocus;
                    e.currentTarget.style.boxShadow = `0 0 0 2px ${ACCENT}`;
                  }}
                  onBlur={(e) => {
                    e.currentTarget.style.backgroundColor = fieldBg;
                    e.currentTarget.style.boxShadow = 'none';
                  }}
                  name="login_user"
                  value={loginData.login_user}
                  onChange={handleLoginChange}
                  required
                />
              </div>
            </div>

            <div className="mb-2" data-aos="fade-up" data-aos-delay="400">
              <label htmlFor="pwd-user" className="form-label small fw-bold mb-1" style={{ color: 'var(--app-text-muted)' }}>
                Mot de passe
              </label>
              <div className="input-group input-group-sm">
                <span className="input-group-text border-0 px-2" style={{ backgroundColor: fieldBg, color: 'var(--app-text-muted)' }}>
                  <MdLock />
                </span>
                <input
                  id="pwd-user"
                  type="password"
                  className="form-control border-0 fs-6 shadow-none"
                  style={{ borderRadius: '0 8px 8px 0', backgroundColor: fieldBg, color: 'var(--app-text)', transition: 'all 0.3s ease' }}
                  onFocus={(e) => {
                    e.currentTarget.style.backgroundColor = fieldBgFocus;
                    e.currentTarget.style.boxShadow = `0 0 0 2px ${ACCENT}`;
                  }}
                  onBlur={(e) => {
                    e.currentTarget.style.backgroundColor = fieldBg;
                    e.currentTarget.style.boxShadow = 'none';
                  }}
                  name="pwd_user"
                  value={loginData.pwd_user}
                  onChange={handleLoginChange}
                  required
                />
              </div>
            </div>

            <div className="mb-3 text-end" data-aos="fade-up" data-aos-delay="450">
              <button
                type="button"
                onClick={() => switchMode('recovery')}
                className="btn btn-link btn-sm p-0 text-decoration-none d-inline-flex align-items-center gap-1"
                style={{ color: RED, fontSize: '0.78rem' }}
              >
                <MdHelpOutline size={14} /> Matricule ou mot de passe oublié ?
              </button>
            </div>

            <div data-aos="fade-up" data-aos-delay="500">
              <button
                type="submit"
                className="btn w-100 mt-1 py-2 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2"
                disabled={loading}
                style={{
                  backgroundColor: ACCENT,
                  color: 'var(--app-text-on-primary)',
                  border: 'none',
                  fontSize: '0.9rem',
                  height: '44px',
                  minHeight: '44px',
                  transition: 'all 0.2s ease-in-out'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.backgroundColor = ACCENT_HOVER;
                  e.currentTarget.style.transform = 'translateY(-1px)';
                  e.currentTarget.style.boxShadow = `0 4px 12px rgba(14, 143, 116, 0.3)`;
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.backgroundColor = ACCENT;
                  e.currentTarget.style.transform = 'translateY(0)';
                  e.currentTarget.style.boxShadow = 'none';
                }}
              >
                {loading ? (
                  <span className="spinner-border spinner-border-sm" style={{ width: '1rem', height: '1rem' }}></span>
                ) : (
                  <>Connexion <MdArrowForward /></>
                )}
              </button>
            </div>
          </form>
        ) : (
          <form onSubmit={handleRecovery} className="recovery-form">
            <p className="small mb-2 text-center" style={{ color: 'var(--app-text-muted)' }} data-aos="fade-up" data-aos-delay="250">
              Renseignez votre matricule et votre email, nous vous enverrons vos identifiants.
            </p>
            <p className="small mb-3 text-center fst-italic" style={{ color: ACCENT }} data-aos="fade-up" data-aos-delay="280">
              Vos identifiants de connexion vous seront envoyés par email.
            </p>

            <div className="mb-3" data-aos="fade-up" data-aos-delay="300">
              <label htmlFor="nom-user" className="form-label small fw-bold mb-1" style={{ color: 'var(--app-text-muted)' }}>
                Matricule
              </label>
              <div className="input-group input-group-sm">
                <span className="input-group-text border-0 px-2" style={{ backgroundColor: fieldBg, color: 'var(--app-text-muted)' }}>
                  <MdPerson />
                </span>
                <input
                  id="nom-user"
                  type="text"
                  className="form-control border-0 fs-6 shadow-none"
                  style={{ borderRadius: '0 8px 8px 0', backgroundColor: fieldBg, color: 'var(--app-text)' }}
                  onFocus={(e) => {
                    e.currentTarget.style.backgroundColor = fieldBgFocus;
                    e.currentTarget.style.boxShadow = `0 0 0 2px ${ACCENT}`;
                  }}
                  onBlur={(e) => {
                    e.currentTarget.style.backgroundColor = fieldBg;
                    e.currentTarget.style.boxShadow = 'none';
                  }}
                  name="nom_user"
                  value={recoveryData.nom_user}
                  onChange={handleRecoveryChange}
                  required
                />
              </div>
            </div>

            <div className="mb-3" data-aos="fade-up" data-aos-delay="350">
              <label htmlFor="email-user" className="form-label small fw-bold mb-1" style={{ color: 'var(--app-text-muted)' }}>
                Email
              </label>
              <div className="input-group input-group-sm">
                <span className="input-group-text border-0 px-2" style={{ backgroundColor: fieldBg, color: 'var(--app-text-muted)' }}>
                  <MdEmail />
                </span>
                <input
                  id="email-user"
                  type="email"
                  className="form-control border-0 fs-6 shadow-none"
                  style={{ borderRadius: '0 8px 8px 0', backgroundColor: fieldBg, color: 'var(--app-text)' }}
                  onFocus={(e) => {
                    e.currentTarget.style.backgroundColor = fieldBgFocus;
                    e.currentTarget.style.boxShadow = `0 0 0 2px ${ACCENT}`;
                  }}
                  onBlur={(e) => {
                    e.currentTarget.style.backgroundColor = fieldBg;
                    e.currentTarget.style.boxShadow = 'none';
                  }}
                  name="email_user"
                  value={recoveryData.email_user}
                  onChange={handleRecoveryChange}
                  required
                />
              </div>
            </div>

            <div className="d-flex gap-2" data-aos="fade-up" data-aos-delay="450">
              <button
                type="button"
                onClick={() => switchMode('login')}
                className="btn rounded-pill fw-bold d-flex align-items-center justify-content-center gap-1"
                style={{
                  flex: '0 0 auto',
                  backgroundColor: 'transparent',
                  color: 'var(--app-text)',
                  border: '1px solid var(--app-border)',
                  fontSize: '0.85rem',
                  height: '44px',
                  padding: '0 16px'
                }}
              >
                <MdArrowBack size={16} /> Retour
              </button>
              <button
                type="submit"
                className="btn flex-grow-1 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2"
                disabled={loading}
                style={{
                  backgroundColor: ACCENT,
                  color: 'var(--app-text-on-primary)',
                  border: 'none',
                  fontSize: '0.9rem',
                  height: '44px'
                }}
              >
                {loading ? (
                  <span className="spinner-border spinner-border-sm" style={{ width: '1rem', height: '1rem' }}></span>
                ) : (
                  'Valider'
                )}
              </button>
            </div>
          </form>
        )}
      </div>
    </div>
  );
}