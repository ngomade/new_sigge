import { Outlet } from "react-router-dom";
import Sidebar from "../sidebar/Sidebar";
import '../../pages/admin/App.scss'
const BaseLayout = () => {
  return (
    <main className="page-wrapper">
      {/* left of page */}
      <Sidebar />
      {/* right side/content of the page */}
      <div className="content-wrapper">
        <Outlet />
      </div>
    </main>
  );
};

export default BaseLayout;
