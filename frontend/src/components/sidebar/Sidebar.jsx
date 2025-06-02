import { useEffect, useRef, useState } from "react";
import {
  MdOutlineClose,
  MdOutlineGridView,
  MdOutlineLogout,
  MdOutlineSettings,
} from "react-icons/md";
import { persistor } from '../../app/store';
import { useDispatch } from 'react-redux';
import { logout } from '../../app/modules/candidate';

import { Link, useHref } from "react-router-dom";
import "./Sidebar.scss";
import {SidebarContext } from "../context/SidebarContext";
import { LuUser, LuUsers2 } from "react-icons/lu";



const Sidebar = () => {
  const dispatch = useDispatch()

  function logoutAction() {
    dispatch(logout())
    persistor.purge()
    sessionStorage.removeItem('candidat');
    window.location.href ='/'
  }
  //const { theme } = useContext(ThemeContext);
  const [isSidebarOpen, closeSidebar ] = useState(SidebarContext);
  const navbarRef = useRef(null);
  const path = useHref()
  // closing the navbar when clicked outside the sidebar area
  const handleClickOutside = (event) => {
    if (
      navbarRef.current &&
      !navbarRef.current.contains(event.target) &&
      event.target.className !== "sidebar-open-btn"
    ) {
      closeSidebar()
    }
  };

  useEffect(() => {
    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  return (
    <nav
      className={`sidebar ${isSidebarOpen ? "sidebar-show" : ""}`}
      ref={navbarRef}
    >
      <div className="sidebar-top">
        <div className="sidebar-brand">
         {/*  <img src={theme === LIGHT_THEME ? LogoBlue : LogoWhite} alt="" /> */}
          <span className="sidebar-brand-text">
            ESTLC Administration
          </span>
        </div>
        <button className="sidebar-close-btn" onClick={closeSidebar}>
          <MdOutlineClose size={24} />
        </button>
      </div>
      <div className="sidebar-body">
        <div className="sidebar-menu">
          <ul className="menu-list">
            <li className="menu-item">
              <Link to="/admin" className={path==="/admin/dashboard"?"menu-link active":"menu-link "}>
                <span className="menu-link-icon">
                  <MdOutlineGridView size={18} />
                </span>
                <span className="menu-link-text">Tableau de bord</span>
              </Link>
            </li>
            <li className="menu-item">
              <Link to="/admin/candidates" className={path==="/admin/candidates"?"menu-link active":"menu-link "} >
                <span className="menu-link-icon">
                  <LuUsers2 size={20} />
                </span>
                <span className="menu-link-text">Candidats</span>
              </Link>
            </li>
            <li className="menu-item">
              <Link to="/admin/comptes" className={path==="/admin/comptes"?"menu-link active":"menu-link "}>
                <span className="menu-link-icon">
                  <LuUser size={20} />
                </span>
                <span className="menu-link-text">Comptes</span>
              </Link>
            </li>
            {/* <li className="menu-item">
              <Link to="/" className="menu-link">
                <span className="menu-link-icon">
                  <MdOutlineCurrencyExchange size={18} />
                </span>
                <span className="menu-link-text">Transactions</span>
              </Link>
            </li>
            <li className="menu-item">
              <Link to="/" className="menu-link">
                <span className="menu-link-icon">
                  <MdOutlineShoppingBag size={20} />
                </span>
                <span className="menu-link-text">Products</span>
              </Link>
            </li>
            <li className="menu-item">
              <Link to="/" className="menu-link">
                <span className="menu-link-icon">
                  <MdOutlinePeople size={20} />
                </span>
                <span className="menu-link-text">Customer</span>
              </Link>
            </li>
            <li className="menu-item">
              <Link to="/" className="menu-link">
                <span className="menu-link-icon">
                  <MdOutlineMessage size={18} />
                </span>
                <span className="menu-link-text">Messages</span>
              </Link>
            </li>
             */}
          </ul>
        </div>

        <div className="sidebar-menu sidebar-menu2">
          <ul className="menu-list">
            <li className="menu-item">
              <Link to="/admin/setting"  className={path==="/admin/setting"?"menu-link active":"menu-link "}>
                <span className="menu-link-icon">
                  <MdOutlineSettings size={20} />
                </span>
                <span className="menu-link-text">Paramètres</span>
              </Link>
            </li>
            <li className="menu-item">
              <Link to={'/logout'} className="menu-link" onClick={logoutAction}>
                <span className="menu-link-icon">
                  <MdOutlineLogout size={20} />
                </span>
                <span className="menu-link-text">Déconnexion</span>
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  );
};

export default Sidebar;
