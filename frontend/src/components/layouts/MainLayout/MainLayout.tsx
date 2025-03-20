import {Outlet} from "react-router-dom";
import {Navbar} from "../../layouts/Navbar/index.ts"

function MainLayout() {
    return (
        <div className="px-[10px] sm:px-[20px] md:px-[80px] lg:px-[110px] mx-auto">
            <Navbar/>
            <Outlet/>
        </div>
    );
}

export default MainLayout;
