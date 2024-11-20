# GitHubViewsCounter

![GitHubViewsCounter](logo/logo_v1_0_0.png)


## About

**GitHubViewsCounter** is an open-source, lightweight tool to track and display the total views of GitHub repositories or profiles in real-time. Designed to work seamlessly in GitHub README files, it dynamically updates every time someone visits your repository or profile.


- [About](#about)
- [Features](#features)
- [Challenges and Design Decisions](#challenges-and-design-decisions)
- [Getting Started](#getting-started)
  - [Clone the Repository](#clone-the-repository)
  - [Set Up a Local Server](#set-up-a-local-server)
  - [Adjust Permissions](#adjust-permissions)
- [Platforms Tested](#platforms-tested)
- [Live Demo and Examples](#live-demo-and-examples)
- [Customization](#customization)
  - [Themes](#themes)
  - [Custom Colors](#custom-colors)
    - [Example Repo Customization](#example-repo-customization)
    - [Example Main Profile Customization](#example-main-profile-customization)
- [Usage](#usage)
  - [For a Repository Specific Counter](#for-a-repository-specific-counter)
  - [For a Main Profile Counter](#for-a-main-profile-counter)
- [Technology Stack](#technology-stack)
- [Contributing](#contributing)
- [Acknowledgments](#acknowledgments)
- [Bug Reporting](#bug-reporting)
- [License](#license)
- [Code of Conduct](#code-of-conduct)
- [Contact](#contact)

## Features

1. **Total Views Counter**:
   - Displays the total number of views for a single repository or all repositories combined for a user.

2. **Sorted Repositories**:
   - Repositories are sorted by views (highest to lowest) in the main profile counter.

3. **Customizable Themes**:
   - Offers `light`, `dark`, and fully customizable themes (background, text, and border colors).

4. **Dynamic Updates**:
   - Automatically updates view counts whenever the counter URL is accessed.

5. **User Tracker**:
   - Tracks the total number of unique users utilizing GitHubViewsCounter, displayed in the counters.

6. **Simple and Lightweight**:
   - No database dependencies — uses JSON files for data storage, ensuring speed and simplicity.

7. **Designed for GitHub**:
   - Perfect for embedding in GitHub README files for profiles or repositories.

8. **Ready to use**:
   - It's easy and ready to use without self hosting, all you need to do is to use our link in your Readne.md, for your main profile or any repositoory that you have.



## Challenges and Design Decisions

### 1. Why Can't We Detect Visitors by Country?
Initially, we aimed to track visitor countries using IP-based geolocation, but encountered the following issues:
   - **GitHub Proxy Servers**:
     - GitHub serves README images via its own proxy (e.g., `camo.githubusercontent.com`), which obscures the original IP address of visitors.
     - As a result, all visits appear to originate from GitHub's servers, making geolocation impossible.
   - **Privacy Compliance**:
     - Even if real IPs were accessible, using them could violate GitHub's privacy policies and laws like GDPR.

**Solution**: We simplified the system to focus on counting overall views instead of tracking visitor locations.

### 2. Dynamic View Calculation
   - To ensure data integrity, we calculate total views dynamically by summing all repository views instead of relying on static counters:
     ```php
     $userData['total_views'] = array_sum(array_column($userData['repositories'], 'views'));
     ```

### 3. Sorting Repositories
   - Repositories are automatically sorted by views in descending order for better visibility in the profile-wide counter:
     ```php
     uasort($userData['repositories'], function ($a, $b) {
         return $b['views'] - $a['views'];
     });
     ```

### 4. Modular Structure
   - The project is organized for scalability and maintainability, with separate scripts for repository-specific and profile-wide counters.

## Getting Started


### 1. **Clone the Repository**:
   ```bash
   git clone https://github.com/openlab-x/GitHubViewsCounter.git
   cd GitHubViewsCounter
   ```

### 2. **Set Up a Local Server**:
   - Use PHP’s built-in server:
     ```bash
     php -S localhost:8000 -t public/
     ```
   - Or deploy on Apache/Nginx.

   - Or simply use XAMPP on Windows or Mac or Linux

### 3. **Adjust Permissions**:
   ```bash
   chmod -R 755 data/
   ```


## Platforms Tested
- [x] Web: Fully functional on major browsers like Chrome, Firefox, and Edge.
- [x] Github Readme-md.
- [x] Local Readme-md.
- [x] Visual studio code readme preview.
- [x] As an external image for any webstie.
- [x] As an Iframe.

## Live Demo and Examples


  - **Light Mode - Live:**
  - https://github.com/openlab-x/GitHubViewsCounter
  - ![GitHub Stats](http://localhost/githubviewscounter/api/gitvcr.php?username=openlab-x&repository=GitHubViewsCounter&theme=light)
  - [Made With GitHubViewsCounter](https://github.com/openlab-x/GitHubViewsCounter)

<br>

  - **Dark Mode - Live:**
  - https://github.com/openlab-x
  - ![GitHub Stats](http://localhost/githubviewscounter/api/gitvcmp.php?username=openlab-x&theme=dark)
  - [Made With GitHubViewsCounter](https://github.com/openlab-x/GitHubViewsCounter)

<br>


## Customization

### Themes
- `theme=light`: Default light theme.
- `theme=dark`: A darker theme for better contrast on dark backgrounds.


### Custom Colors
You can define custom colors using hex values for the following parameters:
| Parameter | Description |
| --- | --- |
| `bgColor` | Background color (e.g., `bgColor=222222`) |
| `textColor` | Text color (e.g., `textColor=FFFFFF`) |
| `borderColor` | Border color (e.g., `borderColor=FF0000`) |

#### Example Repo Customization
```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=demo&repository=MyAwesomeRepo&bgColor=333333&textColor=00FF00&borderColor=FF0000)
```
#### Example Main Profile Customization
```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcmp.php?username=demo&bgColor=333333&textColor=00FF00&borderColor=FF0000)
```




## Usage

### For a Repository Specific Counter

Add the following Markdown to your repository README and be sure it's `/api/gitvcr.php?`:

```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=yourusername&repository=yourrepo&theme=light)

  - [Made With GitHubViewsCounter](https://github.com/openlab-x/GitHubViewsCounter)
```

#### Example:
```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcr.php?username=demo&repository=MyAwesomeRepo&theme=dark)

  - [Made With GitHubViewsCounter](https://github.com/openlab-x/GitHubViewsCounter)
```

### For a Main Profile Counter

To display total views across all your repositories and be sure it's `/api/gitvcmp.php?`:

```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcmp.php?username=yourusername&theme=light)

  - [Made With GitHubViewsCounter](https://github.com/openlab-x/GitHubViewsCounter)
```

#### Example:
```markdown
![GitHubViewsCounter](https://openlabx.com/githubviewscounter/api/gitvcmp.php?username=demo&theme=dark)

  - [Made With GitHubViewsCounter](https://github.com/openlab-x/GitHubViewsCounter)
```





## Technology Stack
- PHP: The core programming language for processing view counts and generating dynamic images.
- GD Library: Used for creating and rendering images with custom text and themes.
- JSON: Lightweight and efficient storage format for user and repository data.
- HTML/Markdown: To embed the counters as images in GitHub README files.
- GitHub Proxy Handling: Ensures compatibility with GitHub’s camo.githubusercontent.com proxy for image requests.
- Custom Theming: Allows developers to specify colors and styles dynamically via URL parameters.

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

- All Contributors: Thanks to everyone who contributed to the project.
- OpenLabX Team: Special thanks to the team for developing and maintaining the project.



## Bug Reporting
- If you find a bug in this project, please do not hesistate to reach out to our team
- If you are feeling helpful, please consider fixing the bug and making a pull request
- We give our greatest thanks to any people who report or fix bugs in this project



## License
This project is licensed under the [MIT license](LICENSE).

## Code of conduct

We are committed to fostering an open and welcoming environment. All participants in this project are expected to adhere to our [Code of Conduct](CODE_OF_CONDUCT.md), which outlines our expectations for respectful behavior and the steps for reporting unacceptable conduct.

## Contact

In pursuit of innovation,
**OpenLabX Team**

- **Website**: [https://openlabx.com](https://openlabx.com)
- **Email**: contact@openlabx.com



**Follow Us:**

<div align="center">
| <a href="https://www.instagram.com/openlabx_official/" target="_blank"><strong>Instagram</strong></a> |
<a href="https://x.com/openlabx" target="_blank"><strong>X (formerly Twitter)</strong></a> |
<a href="https://www.facebook.com/openlabx/" target="_blank"><strong>Facebook</strong></a> |
<a href="https://www.youtube.com/@OpenLabX" target="_blank"><strong>YouTube</strong></a> |
<a href="https://github.com/openlab-x" target="_blank"><strong>GitHub</strong></a> |
</div>

<br>

- [This README.md Made With ReadMeX](https://github.com/openlab-x/ReadMeX)
