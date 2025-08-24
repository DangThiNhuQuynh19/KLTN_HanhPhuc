document.addEventListener("DOMContentLoaded", () => {
  // Add animation classes to elements
  const statCards = document.querySelectorAll(".stat-card")
  statCards.forEach((card, index) => {
    setTimeout(() => {
      card.style.opacity = "0"
      card.style.transform = "translateY(20px)"
      card.style.transition = "all 0.5s ease"

      setTimeout(() => {
        card.style.opacity = "1"
        card.style.transform = "translateY(0)"
      }, 100)
    }, index * 100)
  })

  // Toggle user dropdown menu
  const userInfo = document.querySelector(".user-info")
  const dropdownMenu = document.querySelector(".dropdown-menu")

  if (userInfo && dropdownMenu) {
    userInfo.addEventListener("click", (e) => {
      e.stopPropagation()
      dropdownMenu.classList.toggle("show")
    })

    document.addEventListener("click", () => {
      dropdownMenu.classList.remove("show")
    })
  }

  // Add active class to current tab
  const tabLinks = document.querySelectorAll(".tab-link")

  if (tabLinks.length > 0) {
    tabLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault()
        tabLinks.forEach((l) => l.classList.remove("active"))
        this.classList.add("active")

        // Show corresponding tab content
        const tabId = this.getAttribute("href").split("=").pop()
        const tabContents = document.querySelectorAll(".tab-content > div")
        tabContents.forEach((content) => {
          content.style.display = "none"
        })
        document.getElementById(tabId).style.display = "block"
      })
    })
  }

  // Message input focus
  const messageInput = document.querySelector(".message-input")

  if (messageInput) {
    messageInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        e.preventDefault()
        const messageForm = this.closest(".message-form")
        if (messageForm) {
          messageForm.dispatchEvent(new Event("submit"))
        }
      }
    })
  }

  // Message form submit
  const messageForm = document.querySelector(".message-form")

  if (messageForm) {
    messageForm.addEventListener("submit", function (e) {
      e.preventDefault()
      const input = this.querySelector(".message-input")
      const message = input.value.trim()

      if (message) {
        // In a real app, you would send the message to the server
        // For demo purposes, we'll just add it to the UI
        const messageBody = document.querySelector(".message-body")
        const now = new Date()
        const timeString =
          now.getHours().toString().padStart(2, "0") + ":" + now.getMinutes().toString().padStart(2, "0")

        const messageBubble = document.createElement("div")
        messageBubble.className = "message-bubble sent"
        messageBubble.innerHTML = `
                    <div class="message-content">
                        <div class="message-text">${message}</div>
                        <div class="message-time">${timeString}</div>
                    </div>
                `

        messageBody.appendChild(messageBubble)
        messageBody.scrollTop = messageBody.scrollHeight
        input.value = ""

        // Simulate response after 1 second
        setTimeout(() => {
          const responseMessage = document.createElement("div")
          responseMessage.className = "message-bubble received"
          responseMessage.innerHTML = `
            <div class="message-avatar">
              <div class="avatar-placeholder">NVA</div>
            </div>
            <div class="message-content">
              <div class="message-text">Cảm ơn bác sĩ đã phản hồi. Tôi sẽ làm theo hướng dẫn.</div>
              <div class="message-time">${timeString}</div>
            </div>
          `
          messageBody.appendChild(responseMessage)
          messageBody.scrollTop = messageBody.scrollHeight
        }, 1000)
      }
    })
  }

  // Add hover effects to table rows
  const tableRows = document.querySelectorAll(".data-table tbody tr")
  if (tableRows.length > 0) {
    tableRows.forEach((row) => {
      row.addEventListener("mouseenter", () => {
        row.style.transform = "translateY(-2px)"
        row.style.boxShadow = "0 4px 6px -1px rgba(0, 0, 0, 0.1)"
      })

      row.addEventListener("mouseleave", () => {
        row.style.transform = "translateY(0)"
        row.style.boxShadow = "none"
      })
    })
  }
})
