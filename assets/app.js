import "./bootstrap.js";
import "./styles/app.css";
import AutoRedirect from "./js/autoredirect.js";

let redirect = new AutoRedirect("#counter", 30, "/");
