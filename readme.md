# E-Commerce Website PHP made by Pritish Purav

## Overview

This PHP-based E-Commerce website, created by Pritish Purav, integrates Razorpay for payments and Google Auth for user authentication. The application uses a MySQL database to store user and product information.

## Features

- **User Authentication**
  - Login with Google Auth
  - Secure user authentication and authorization

- **Shopping Cart**
  - Add, update, and remove items
  - View total cost of items

- **Checkout**
  - Enter shipping details
  - Review and confirm the order

- **Payment Integration**
  - Razorpay payment gateway
  - Support for multiple payment options

## Dependencies
- [XAMPP Server](https://www.apachefriends.org/index.html)
- [Razorpay API Key](https://dashboard.razorpay.com/)
- [Google OAuth Client ID and Client Secret](https://console.cloud.google.com/)

## Demo
![Demo](https://cdn.pritishpurav.in/Readme_Assets/Ecommerce_php_website.MP4)
## Setup
1. **Install XAMPP Server:**
    - Download and install XAMPP from [here](https://www.apachefriends.org/index.html)

2. **Clone the repository:**

    ```bash
    git clone https://github.com/pritishpurav/e-commerce-website.git
    ```

3. **Move shop folder to htdocs folder in XAMPP:**
    - Open XAMPP and click on the "Explorer" button in the Left Column
    - Move the `shop` folder to the `htdocs` folder

4. **Start Apache and MySQL in XAMPP:**
    - Open XAMPP and click on the "Start" button for Apache and MySQL

5. **Import Database:**
    - Open `phpmyadmin` in your browser by going to `http://localhost/phpmyadmin`
    - Create a new database called `data`
    - Click on the `data` database and then click on the `Import` tab
    - Import the `data.sql` file into the `data` database

6. **Get Google OAuth Credentials:**
    - Go to the [Google Cloud Console](https://console.cloud.google.com/)
    - Create a new project
    - Go to the `Credentials` tab and create a new `OAuth 2.0 Client ID`
    - Add `http://localhost/shop/login.php` as authorized redirect URIs
    - Copy the `Client ID` and `Client Secret` and paste them in the `config.php` file

7. **Get Razorpay API Credentials:**
    - Go to the [Razorpay Dashboard](https://dashboard.razorpay.com/)
    - Go to the `Settings` tab and click on `API Keys`
    - Copy the `Key ID` and paste them in the `config.php` file

8. **Run the application:**
    - Open your browser and go to `http://localhost/shop/`

## Contribution

Contributions are welcome! Open issues or pull requests for enhancements or bug fixes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.