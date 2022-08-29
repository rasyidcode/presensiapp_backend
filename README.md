Presensi App Backend
======================
> Presensi App Backend is an admin panel to maintain [Presensi App](https://github.com/rasyidcode/flutter_presensi_mhs) & [Dosen QRCode App](https://github.com/rasyidcode/flutter_qrcode_dsn) which was built using Flutter.

### Stacks
- CodeIgniter 4
- AJAX
- Bootstrap 5
- Admin LTE 3

### Project Structure
    .
    ├── app                     # ci app folder
        ...
    ├── envs                    # env files
    ├── Modules
        ├── Admin               # admin module, for admin panel
            ├── SubModule       # each sub module has its own controllers, models and views
                ├── Controllers 
                ├── Models      
                ├── Views       
        ├── Api                 # api module, for REST API
        ├── Pub                 # pub module, for no-need-auth page ex. Login page
        ├── Shared              # shared module, for common functionality shared between module
    ├── public                  # ci public folder
    ├── system                  # ci system folder
    ├── vendor                  # composer stuff
    ├── writeable               # ci writeable (I never use this)

> The project structure above is called HMVC, which stands for Hierarical Model View Controller, which will divided each feature on its own module
### Features
- Auth
- Dashboard
- User Management
- Master Data Management
- Student Management
- Teacher Management
- Schedule Management
- Present Monitorer

### REST API
-

### DB Structure
- 

### Screenshots
-