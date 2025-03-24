import { Button } from "@/components/ui/button";
import { Link } from "@inertiajs/react";

export function Navbar() {
    return (
        <nav className="backdrop-blur-md  w-full   fixed  z-50 border-b ">
            <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-24">
                {/* Logo - Left Side */}
                <div className="flex items-center">
                    <Link href="/" className="flex items-center space-x-2">
                        <img src="/img/yui.png" alt="yui" width={36} />
                        <span className="text-xl font-bold">yui</span>
                    </Link>
                </div>

                {/* Navigation Links - Center */}
                <div className="flex items-center justify-center flex-1">
                    <div className="flex space-x-8">
                        <Link
                            href="/about"
                            className="text-md font-bold text-gray-600 transition-colors hover:text-blue-600 "
                        >
                            About
                        </Link>
                        <Link
                            href="/usage"
                            className="text-md font-bold text-gray-600 transition-colors hover:text-blue-600"
                        >
                            Usage
                        </Link>
                    </div>
                </div>

                {/* Get Started Button - Right Side */}
                <div className="flex">
                    <a
                        href="/login"
                        className="bg-black text-white px-6 py-2 rounded-sm hover:bg-gray-600"
                    >
                        Sign in
                    </a>
                </div>
            </div>
        </nav>
    );
}
