

# GitHubViewsCounter

![GitHubViewsCounter](logo/logo_v1_0_0.png)

---

## About

**GitHubViewsCounter** is an open-source, lightweight tool to track and display the total views of GitHub repositories or profiles in real-time. It is designed to work seamlessly in GitHub README files and dynamically updates every time someone visits your repository or profile.


## Table of Contents

- [Features](#features)
- [Live Demo and Examples](#live-demo-and-examples)
- [Getting Started](#getting-started)
- [Usage](#usage)
  - [Repository View Counter](#repository-view-counter)
  - [Profile View Counter](#profile-view-counter)
- [Customization](#customization)
- [Technology Stack](#technology-stack)
- [Challenges and Design Decisions](#challenges-and-design-decisions)
- [Acknowledgments](#acknowledgments)
- [Bug Reporting](#bug-reporting)
- [License](#license)
- [Code of Conduct](#code-of-conduct)
- [Contact](#contact)



## Features

1. **Dynamic Total Views Counter**:
   - Automatically updates when someone accesses your GitHub README.md.

2. **Valid Repository Verification**:
   - Ensures valid repositories and usernames by sending an **HTTP HEAD request** to verify repository existence.

3. **Customizable Themes**:
   - Offers `light`, `dark`, and fully customizable themes (background, text, and border colors).

4. **Responsive Design**:
   - Works on any screen size and aligns perfectly in GitHub README files.

5. **Sorted Repositories**:
   - Displays repositories sorted by views (highest to lowest).

6. **Protected Data**:
   - Uses IP-based proxy-safe tracking, compatible with GitHub's `camo` system.

7. **Live Counter for Users**:
   - Tracks the total number of users utilizing GitHubViewsCounter, displayed on counters.



## Live Demo and Examples

### Live Demos

- **Repository View Counter (Light Theme)**:
  ```markdown
  ![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=openlab-x&repository=OpenQRCode&theme=light)
  ```
  **Preview**:
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=openlab-x&repository=OpenQRCode&theme=light) [<img alt="Made With GitHubViewsCounter" src="https://openlabx.com/githubviewscounter/api/footer_image.php?theme=light" />](https://github.com/openlab-x/GitHubViewsCounter)

<br>

- **Repository View Counter if the repository doesn't exist**:
  ```markdown
  ![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=openlab-x&repository=OpenQRCode&theme=light)
  ```
  **Preview**:
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=openlab-x&repository=ImagineDragon&theme=light) [<img alt="Made With GitHubViewsCounter" src="https://openlabx.com/githubviewscounter/api/footer_image.php?theme=light" />](https://github.com/openlab-x/GitHubViewsCounter)

<br>

- **Profile View Counter (Dark Theme)**:
  ```markdown
  ![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcmp.php?username=openlab-x&theme=dark)
  ```
  **Preview**:
  ![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcmp.php?username=openlab-x&theme=dark)

<br>

- **Profile View Counter if the username doesn't exist**:
  ```markdown
  ![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcmp.php?username=CuttyGirl69&theme=dark)
  ```
  **Preview**:
  ![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcmp.php?username=CuttyGirl69&theme=dark)


---

## Getting Started

### 1. **Clone the Repository**
```bash
git clone https://github.com/openlab-x/GitHubViewsCounter.git
cd GitHubViewsCounter
```

### 2. **Set Up a Local Server**
- Use PHP’s built-in server:
  ```bash
  php -S localhost:8000 -t api/
  ```
- Alternatively, deploy on **Apache/Nginx** or use **XAMPP**.

### 3. **Adjust Permissions**
Ensure the `data/` directory is writable:
```bash
chmod -R 755 data/
```

---

## Usage

### Repository View Counter

To add a view counter for a specific repository, use the following Markdown:
```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=yourusername&repository=yourrepo&theme=light)
```

#### Example:
```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=openlab-x&repository=OpenQRCode&theme=dark)
```

- [Made With GitHubViewsCounter](https://github.com/openlab-x/GitHubViewsCounter)

---

### Profile View Counter

To display total views across all repositories:
```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcmp.php?username=yourusername&theme=dark)
```

#### Example:
```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcmp.php?username=openlab-x&theme=light)
```

- [Made With GitHubViewsCounter](https://github.com/openlab-x/GitHubViewsCounter)

---

## Customization

### Themes
- `theme=light`: Default light theme.
- `theme=dark`: A darker theme.


### Custom Colors
You can define custom colors using hex values for the following parameters:
| Parameter | Description |
| --- | --- |
| `bgColor` | Background color (e.g., `bgColor=222222`) |
| `textColor` | Text color (e.g., `textColor=FFFFFF`) |
| `borderColor` | Border color (e.g., `borderColor=FF0000`) |

#### Example:
```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=openlab-x&repository=OpenQRCode&bgColor=333333&textColor=00FF00&borderColor=FF0000)
```

---

## Technology Stack

- **PHP**: Core backend logic for real-time tracking and dynamic image generation.
- **GD Library**: Creates visually appealing and customizable counters.
- **GitHub Proxy Handling**: Ensures compatibility with GitHub’s camo.githubusercontent.com proxy for image requests.
- **cURL**: Validates repositories and usernames by sending an **HTTP HEAD request**.
- **JSON**: Lightweight storage for tracking data.
- **HTML/Markdown**: For embedding counters into GitHub README files.
- **Custom Theming**: Allows developers to specify colors and styles dynamically via URL parameters.




## Challenges and Design Decisions

1. **No Country-Based Tracking**
   - GitHub proxies images, masking visitor IPs. As a result, geolocation is impossible, as we wanted to display the views as based on country locations and show more details.
   - To maintain simplicity and privacy, GitHubViewsCounter focuses solely on view counts.

2. **Repository and Username Validation**
   - Ensures users cannot add repositories or usernames they don’t own by sending **HTTP HEAD requests** to validate the URLs.

3. **Dynamic Calculations**
   - Total views and repository-specific views are recalculated dynamically for accuracy.

4. **Modular Design**
   - Separate scripts handle profile-wide counters (`gitvcmp.php`) and repository-specific counters (`gitvcr.php`).




## Contributing
 We welcome contributions! Here's how you can help:

  1. Give the project a STAR.
  2. Follow us on Github.
  3. Follow us on Social Media.
  4. Fork the repository.
  5. Create a new branch for your feature or bug fix.
  6. Make your changes.
  7. Submit a pull request.
  8. Please make sure to update tests as appropriate.

## Acknowledgments

Special thanks to the **OpenLabX Team** for developing this project and to all contributors.



## Bug Reporting

If you encounter a bug:
1. Create an issue in the [GitHub Repository](https://github.com/openlab-x/GitHubViewsCounter/issues).
2. Provide a detailed description, including steps to reproduce the issue.



## License

This project is licensed under the [MIT License](LICENSE).



## Code of Conduct

We aim to maintain a welcoming environment. Please follow our [Code of Conduct](CODE_OF_CONDUCT.md).


## Contact

For inquiries, reach out to the **OpenLabX Team**:

- Website: [https://openlabx.com](https://openlabx.com)
- Email: contact@openlabx.com



**Follow Us:**
<div align="center">
<a href="https://www.instagram.com/openlabx_official/" target="_blank"><strong>Instagram</strong></a> |
<a href="https://x.com/openlabx" target="_blank"><strong>X (formerly Twitter)</strong></a> |
<a href="https://www.facebook.com/openlabx/" target="_blank"><strong>Facebook</strong></a> |
<a href="https://www.youtube.com/@OpenLabX" target="_blank"><strong>YouTube</strong></a> |
<a href="https://github.com/openlab-x" target="_blank"><strong>GitHub</strong></a>
</div>

