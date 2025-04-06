document.addEventListener("DOMContentLoaded", function () {
    const API_URL = "http://api.cc.localhost/courses/";
    const cardsContainer = document.getElementById("cardsContainer");
    const sideNav = document.getElementById("sideNav");
  
    fetchCourses(API_URL);
  
    function fetchCourses(url) {
      fetch(url)
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          renderCards(data);
          buildSideNav(data);
        })
        .catch((error) => console.error("Error fetching courses:", error));
    }
  
    function renderCards(courses) {
      cardsContainer.innerHTML = "";
      courses.forEach((course) => {
        const card = document.createElement("div");
        card.className = "card";
  
        const imageWrapper = document.createElement("div");
        imageWrapper.style.position = "relative";
        const img = document.createElement("img");
        img.src = course.preview || "./image/default.jpg";
        imageWrapper.appendChild(img);
  
        const badge = document.createElement("div");
        badge.className = "category-badge";
        badge.textContent = course.main_category_name;
        imageWrapper.appendChild(badge);
  
        card.appendChild(imageWrapper);
  
        const title = document.createElement("h3");
        title.textContent = truncateText(course.name, 20);
        title.addEventListener("click", () => showCourseDetail(course));
        card.appendChild(title);
  
        const desc = document.createElement("p");
        desc.textContent = truncateWords(course.description, 30);
        card.appendChild(desc);
  
        cardsContainer.appendChild(card);
      });
    }
  
    function buildSideNav(courses) {
      // Group courses by category_id and main_category_name
      const groups = {};
      courses.forEach((course) => {
        const catId = course.category_id;
        const catName = course.main_category_name;
        if (!groups[catId]) {
          groups[catId] = {
            category_id: catId,
            main_category_name: catName,
            count_of_courses: 0,
            sub_link: [],
          };
        }
        groups[catId].count_of_courses++;
        groups[catId].sub_link.push({
          id: course.id,
          name: course.name,
        });
      });
  
      sideNav.innerHTML = "";
      Object.values(groups).forEach((group) => {
        // Create main category list item
        const li = document.createElement("li");
        li.textContent = `${group.main_category_name} (${group.count_of_courses})`;
        li.style.fontWeight = "bold";
        li.style.cursor = "pointer";
  
        // When the main category is clicked, set active styling and fetch filtered courses
        li.addEventListener("click", function () {
          setActiveItem(li, () => {
            fetchCourses(`http://api.cc.localhost/categories/${group.category_id}`);
          });
        });
  
        // Create sub-links for each course under this category
        if (group.sub_link.length > 0) {
          const subUl = document.createElement("ul");
          group.sub_link.forEach((item) => {
            const subLi = document.createElement("li");
            subLi.textContent = item.name;
            subLi.style.cursor = "pointer";
            // Stop event propagation so clicking sublink doesn't trigger parent's click
            subLi.addEventListener("click", (e) => {
              e.stopPropagation();
              setActiveItem(subLi, () => showCourseDetailById(item.id));
            });
            subUl.appendChild(subLi);
          });
          li.appendChild(subUl);
        }
        sideNav.appendChild(li);
      });
    }
  
    /**
     * Removes active class from all sidebar list items and sets it on the clicked element.
     * @param {HTMLElement} element - The clicked element.
     * @param {Function} callback - Callback to execute after setting active styling.
     */
    function setActiveItem(element, callback) {
      // Remove active class from all <li> elements within the sidebar
      sideNav.querySelectorAll("li").forEach((el) => el.classList.remove("active"));
      // Add active class to the clicked element
      element.classList.add("active");
      callback();
    }
  
    function showCourseDetail(course) {
      cardsContainer.innerHTML = "";
      const detailCard = document.createElement("div");
      detailCard.className = "card";
  
      const imageWrapper = document.createElement("div");
      imageWrapper.style.position = "relative";
      const img = document.createElement("img");
      img.src = course.preview || "./image/default.jpg";
      imageWrapper.appendChild(img);
  
      const badge = document.createElement("div");
      badge.className = "category-badge";
      badge.textContent = course.main_category_name;
      imageWrapper.appendChild(badge);
  
      detailCard.appendChild(imageWrapper);
  
      const title = document.createElement("h3");
      title.textContent = course.name;
      detailCard.appendChild(title);
  
      const desc = document.createElement("p");
      desc.textContent = course.description;
      detailCard.appendChild(desc);
  
      cardsContainer.appendChild(detailCard);
    }
  
    function showCourseDetailById(courseId) {
      fetch(API_URL)
        .then((response) => response.json())
        .then((data) => {
          const course = data.find((c) => c.id === courseId);
          if (course) {
            showCourseDetail(course);
          }
        })
        .catch((error) => console.error("Error fetching course detail:", error));
    }
  
    function truncateText(text, maxLength) {
      return text.length <= maxLength ? text : text.substring(0, maxLength) + "...";
    }
  
    function truncateWords(text, maxWords) {
      const words = text.split(/\s+/);
      return words.length <= maxWords ? text : words.slice(0, maxWords).join(" ") + "...";
    }
  });
  